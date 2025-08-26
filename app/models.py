from app.app import db
from datetime import datetime
import json

class Indicator(db.Model):
    """Represents a single threat indicator."""
    id = db.Column(db.Integer, primary_key=True)
    value = db.Column(db.String(255), unique=True, nullable=False, index=True)
    type = db.Column(db.String(50), nullable=False, index=True) # e.g., 'ipv4', 'domain'
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    last_analyzed = db.Column(db.DateTime)
    analysis_data = db.Column(db.Text) # Storing as text/JSON

    @property
    def analysis_json(self):
        """
        Safely parses the analysis_data JSON string into a Python dict.
        Returns an empty dict if data is missing or invalid.
        """
        if not self.analysis_data:
            return {}
        try:
            return json.loads(self.analysis_data)
        except json.JSONDecodeError:
            return {}

    def __repr__(self):
        return f'<Indicator {self.type}:{self.value}>'
