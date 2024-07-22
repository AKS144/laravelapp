import os
import requests
from bs4 import BeautifulSoup
import pandas as pd
from sqlalchemy import create_engine
import logging

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Define the URL
url = 'https://www.jaquar.com/en/kubix-prime-sanitaryware'

# Define the directory to save images
image_dir = 'C:\\wamp64\\www\\laravelapp\\product'
os.makedirs(image_dir, exist_ok=True)

try:
    # Send a GET request
    response = requests.get(url)
    response.raise_for_status()  # Raise an exception for HTTP errors
    soup = BeautifulSoup(response.content, 'html.parser')

    # Find product elements
    products = soup.find_all('div', class_='item-box')

    # Check if products are found
    if not products:
        logger.warning("No products found. Please check the HTML structure and class names.")

    # Extract product data
    product_data = []
    for product in products:
        product_item = product.find('div', class_='product-item')
        if product_item:
            sku = product_item.find('div', class_='sku').text.strip() if product_item.find('div', class_='sku') else 'N/A'
            title = product_item.find('h2', class_='product-title').text.strip() if product_item.find('h2', class_='product-title') else 'N/A'
            price = product_item.find('span', class_='price actual-price').text.strip() if product_item.find('span', class_='price actual-price') else 'N/A'
            picture_url = product_item.find('img', class_='picture')['src'] if product_item.find('img', class_='picture') else 'N/A'
            product_id = product_item['data-productid'] if 'data-productid' in product_item.attrs else 'N/A'
            
            # Download the image and save it locally
            if picture_url != 'N/A':
                image_response = requests.get(picture_url)
                if image_response.status_code == 200:
                    image_name = os.path.join(image_dir, os.path.basename(picture_url))
                    with open(image_name, 'wb') as f:
                        f.write(image_response.content)
                    local_picture_path = image_name
                else:
                    local_picture_path = 'N/A'
            else:
                local_picture_path = 'N/A'
            
            product_data.append([product_id, sku, title, price, local_picture_path])

    # Create a DataFrame
    df = pd.DataFrame(product_data, columns=['ProductID', 'SKU', 'Title', 'Price', 'Picture'])
    logger.info(f"Extracted {len(df)} products")

    # Print DataFrame for debugging
    logger.info(df)

    # Save DataFrame to SQL database
    engine = create_engine('mysql+pymysql://root:@127.0.0.1:3306/laravelapp')
    df.to_sql('products', con=engine, if_exists='replace', index=False)

    logger.info('Data saved to database successfully.')

except requests.exceptions.RequestException as e:
    logger.error(f"HTTP error occurred: {e}")
except Exception as e:
    logger.error(f"An error occurred: {e}")
