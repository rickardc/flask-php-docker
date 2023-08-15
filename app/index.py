
# source: https://auth0.com/blog/developing-restful-apis-with-python-and-flask/#-span-id--restful-flask----span--Creating-a-RESTful-Endpoint-with-Flask
from flask import Flask, jsonify, request

app = Flask(__name__)

incomes = [
    { 'description': 'salary', 'amount': 5000 }
]


@app.route('/incomes')
def get_incomes():
    return jsonify(incomes)


@app.route('/incomes', methods=['POST'])
def add_income():
    incomes.append(request.get_json())
    return '', 204