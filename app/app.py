from flask import Flask, jsonify, request
import pandas as pd
import joblib

app = Flask(__name__)

@app.route('/')
def hello():
	return "index page"

@app.route("/predict", methods=['POST'])
def do_prediction():
    json = request.get_json()
    
    #loading saved model here in this python file
    model = joblib.load('model/price_model.pkl')
    
    #creating data frame of JSON data
    df = pd.DataFrame(json, index=[0])
    

    #performing preprocessing steps
    
    scaler = joblib.load('model/scaler.pkl')
    
    x_scaled = scaler.transform(df)

    x_scaled = pd.DataFrame(x_scaled, columns=df.columns)
    y_predict = model.predict(df)

    res= {"Predicted Price of House" : y_predict[0]}
    df_json = df.to_json(orient='records')
    x_scaled_json = x_scaled.to_json(orient='records')
    return res


if __name__ == '__main__':
	app.run(debug=True, host='0.0.0.0', port=8000) # remove debug=True for production
