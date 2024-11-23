import os
import cv2
import numpy as np
from scipy.stats import chisquare
import logging
import pandas as pd
from datetime import datetime

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(message)s')

def benfords_law_test(data):
    observed_counts = [data.count(str(digit)) for digit in range(1, 10)]

    # Check if all observed counts are zero
    if all(count == 0 for count in observed_counts):
        logging.info("All observed counts are zero. Skipping analysis.")
        return None

    expected_counts = [len(data) * np.log10(1 + 1/digit) for digit in range(1, 10)]

    # Normalize counts
    total_observed = sum(observed_counts)
    total_expected = sum(expected_counts)
    
    observed_freq = [count / total_observed for count in observed_counts]
    expected_freq = [count / total_expected for count in expected_counts]

    chi2, p_value = chisquare(f_obs=observed_freq, f_exp=expected_freq)

    return p_value

def analyze_image(image_path):
    img = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
    
    # Check if the image was loaded successfully
    if img is None:
        # logging.error(f"Error loading image: {image_path}")
        return None
    
    flattened_image = img.flatten()
    features = [str(int(value)) for value in flattened_image]

    return features


def main(image_folder):
    data = []

    threshold = 0.96 
    scan_result = ""  # Initialize an empty string to store the current scan result

    for image_file in os.listdir(image_folder):
        if image_file.lower() == ".ds_store":
            continue  # Skip .DS_Store files
        
        image_path = os.path.join(image_folder, image_file)
        
        features = analyze_image(image_path)

        # Check if image analysis was successful
        if features is not None:
            p_value = benfords_law_test(features)
            if p_value is not None:
                result = "Potential deepfake" if p_value < threshold else "Authentic"
            else:
                result = "Skipped analysis"
            
            data.append([datetime.now().strftime('%Y-%m-%d'), datetime.now().strftime('%H:%M:%S'), image_file, p_value, result])
            scan_result += f"{image_file}: {result}\n"  # Append current scan result to the string
        else:
            # Handle the case where image analysis failed
            logging.warning(f"Skipping image analysis for: {image_file}")
            data.append([datetime.now().strftime('%Y-%m-%d'), datetime.now().strftime('%H:%M:%S'), image_file, None, "Skipped analysis"])

    # Load existing CSV data into a DataFrame if the file exists
    csv_file = 'scan_results.csv'
    if os.path.exists(csv_file) and os.stat(csv_file).st_size > 0:  # Check if the CSV file exists and is not empty
        existing_df = pd.read_csv(csv_file)
    else:
        existing_df = pd.DataFrame()

    # Create DataFrame for new data
    new_df = pd.DataFrame(data, columns=['Date', 'Time', 'Image Name', 'PValue', 'Result'])

    # Concatenate new data with existing data
    combined_df = pd.concat([existing_df, new_df], ignore_index=True)

    # Save combined DataFrame as CSV
    combined_df.to_csv(csv_file, index=False, mode='w' if existing_df.empty else 'a', header=existing_df.empty)

    return scan_result  # Return the current scan result as a string


if __name__ == "__main__":
    image_folder = "/Applications/XAMPP/xamppfiles/htdocs/DETEK-AI/uploads"
    scan_result = main(image_folder)
    print(scan_result)  # Print the current scan result
