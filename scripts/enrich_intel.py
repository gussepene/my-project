import requests
import logging
import os
import sys
import json
from datetime import datetime
import time

# --- Path Setup ---
project_root = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
sys.path.insert(0, project_root)

from app.app import app, db
from app.models import Indicator

# --- Configuration ---
LOG_FILE = os.path.join(project_root, 'logs', 'enrich_intel.log')
ENRICHMENT_API_URL = "http://ip-api.com/json/"
# Respect the API rate limit (45 req/min)
REQUEST_DELAY_SECONDS = 1.5

# --- Setup Logging ---
os.makedirs(os.path.dirname(LOG_FILE), exist_ok=True)
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler(LOG_FILE),
        logging.StreamHandler()
    ]
)

def enrich_indicator(indicator):
    """
    Enriches a single indicator using the ip-api.com service.
    Returns True on success, False on failure.
    """
    if indicator.type != 'ipv4':
        logging.warning(f"Skipping enrichment for non-ipv4 indicator: {indicator.value}")
        return False

    url = f"{ENRICHMENT_API_URL}{indicator.value}"
    logging.info(f"Enriching IP: {indicator.value} from {url}")

    try:
        response = requests.get(url)
        response.raise_for_status()

        api_data = response.json()

        if api_data.get('status') == 'success':
            # Store the enrichment data
            indicator.analysis_data = json.dumps(api_data)
            indicator.last_analyzed = datetime.utcnow()
            logging.info(f"Successfully enriched IP: {indicator.value}")
            return True
        else:
            logging.error(f"API returned failure for IP {indicator.value}: {api_data.get('message')}")
            # Mark as analyzed to avoid retrying a bad IP
            indicator.last_analyzed = datetime.utcnow()
            indicator.analysis_data = json.dumps(api_data)
            return False

    except requests.exceptions.RequestException as e:
        logging.error(f"HTTP error during enrichment for IP {indicator.value}: {e}")
        return False
    except json.JSONDecodeError:
        logging.error(f"Failed to decode JSON response for IP {indicator.value}")
        return False

def run_enrichment_cycle():
    """
    Finds indicators that have not been analyzed and enriches them.
    """
    with app.app_context():
        # Get all indicators that have never been analyzed
        indicators_to_enrich = Indicator.query.filter_by(last_analyzed=None).all()

        if not indicators_to_enrich:
            logging.info("No new indicators to enrich.")
            return

        logging.info(f"Found {len(indicators_to_enrich)} indicators to enrich.")

        enriched_count = 0
        for indicator in indicators_to_enrich:
            if enrich_indicator(indicator):
                enriched_count += 1
            # Wait between requests to respect the API rate limit
            time.sleep(REQUEST_DELAY_SECONDS)

        if enriched_count > 0:
            db.session.commit()
            logging.info(f"Committed {enriched_count} enriched indicators to the database.")
        else:
            # Still commit changes for indicators that failed, so we don't retry them
            db.session.commit()
            logging.warning("No indicators were successfully enriched in this cycle.")

if __name__ == "__main__":
    logging.info("--- Starting Threat Intel Enrichment ---")
    run_enrichment_cycle()
    logging.info("--- Threat Intel Enrichment Finished ---")
