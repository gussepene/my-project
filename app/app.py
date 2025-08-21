from flask import Flask
from flask_sqlalchemy import SQLAlchemy
import os

# Get the absolute path of the directory containing app.py
basedir = os.path.abspath(os.path.dirname(__file__))
# This will be the 'app' directory. We want the project root, which is one level up.
project_root = os.path.dirname(basedir)

app = Flask(__name__)
# Configure the SQLite database, placing the .db file at the project root
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///' + os.path.join(project_root, 'threat_intel.db')
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)

# Import models after db is defined to avoid circular imports.
# This makes the models available to SQLAlchemy.
from app import models
from flask import render_template, jsonify

@app.route('/')
def index():
    """Renders the main dashboard page."""
    indicators = models.Indicator.query.order_by(models.Indicator.created_at.desc()).all()
    return render_template('index.html', indicators=indicators)

@app.route('/api/indicators')
def api_indicators():
    """Returns a JSON list of all indicators."""
    indicators = models.Indicator.query.all()
    output = []
    for indicator in indicators:
        output.append({
            'id': indicator.id,
            'value': indicator.value,
            'type': indicator.type,
            'created_at': indicator.created_at.isoformat() if indicator.created_at else None,
            'last_analyzed': indicator.last_analyzed.isoformat() if indicator.last_analyzed else None,
            'analysis': indicator.analysis_json
        })
    return jsonify({'indicators': output})

if __name__ == '__main__':
    app.run(debug=True)
