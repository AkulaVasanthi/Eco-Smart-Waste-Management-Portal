from flask import Flask, request, jsonify
from flask_cors import CORS  # <--- import this
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image
import numpy as np
from PIL import Image

app = Flask(__name__)
CORS(app)  # <--- add this line to enable CORS for all routes

# Load model
model = load_model("waste_classifier_model.keras")

class_names = ['cardboard', 'glass', 'metal', 'paper', 'plastic', 'trash']

@app.route('/predict', methods=['POST'])
def predict():
    if 'file' not in request.files:
        return jsonify({'error': 'No file uploaded'})

    file = request.files['file']
    img = Image.open(file.stream).convert('RGB')
    img = img.resize((224, 224))

    img_array = image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0) / 255.0

    prediction = model.predict(img_array)
    predicted_class = class_names[np.argmax(prediction)]

    return jsonify({'prediction': predicted_class})

if __name__ == '__main__':
    app.run(debug=True)

