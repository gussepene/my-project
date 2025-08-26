import requests
import logging
import os
import sys

# --- Path Setup ---
# Add the project root to the Python path to allow importing from the 'app' module
project_root = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
sys.path.insert(0, project_root)

from app.app import app, db
from app.models import Indicator

# --- Configuration ---
LOG_FILE = os.path.join(project_root, 'logs', 'gather_intel.log')
IP_BLOCKLIST_URL = "https://feodotracker.abuse.ch/downloads/ipblocklist_recommended.txt"

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

def fetch_ip_blocklist():
    """
    Fetches the IP blocklist from the configured URL.
    Returns a list of IP addresses.
    """
    logging.info(f"Fetching IP blocklist from {IP_BLOCKLIST_URL}")
    try:
        response = requests.get(IP_BLOCKLIST_URL)
        response.raise_for_status()
        ips = [
            line.strip() for line in response.text.splitlines()
            if not line.strip().startswith('#') and line.strip()
        ]
        logging.info(f"Successfully fetched {len(ips)} IP addresses from the source.")
        return ips
    except requests.exceptions.RequestException as e:
        logging.error(f"Error fetching IP blocklist: {e}")
        return []

def store_indicators(ip_list):
    """
    Stores a list of IP addresses in the database, avoiding duplicates.
    """
    if not ip_list:
        logging.warning("Indicator list is empty, nothing to store.")
        return

    new_indicator_count = 0
    with app.app_context():
        for ip in ip_list:
            # Check if the indicator already exists
            exists = db.session.query(Indicator.id).filter_by(value=ip, type='ipv4').first() is not None
            if not exists:
                indicator = Indicator(value=ip, type='ipv4')
                db.session.add(indicator)
                new_indicator_count += 1

        if new_indicator_count > 0:
            db.session.commit()
            logging.info(f"Successfully stored {new_indicator_count} new indicators in the database.")
        else:
            logging.info("No new indicators to store.")

if __name__ == "__main__":
    logging.info("--- Starting Threat Intel Gathering ---")
    ip_list = fetch_ip_blocklist()
    store_indicators(ip_list)
    logging.info("--- Threat Intel Gathering Finished ---")
