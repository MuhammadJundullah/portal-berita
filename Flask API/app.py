from flask import Flask, request, jsonify
import requests
import os
import logging
import time
from flask_caching import Cache
import re

app = Flask(__name__)

# Konfigurasi caching untuk menghindari terlalu sering memanggil API
app.config["CACHE_TYPE"] = "simple"
app.config["CACHE_DEFAULT_TIMEOUT"] = 600  # Cache selama 10 menit
cache = Cache(app)

# Logging untuk debugging
logging.basicConfig(level=logging.INFO)

NEWS_API_KEY = "7c5e9c08427f462bb9ddb8b1a7737485"
if not NEWS_API_KEY:
    raise ValueError("NEWS_API_KEY tidak ditemukan. Harap set di environment variables.")

def fetch_news():
    """ Ambil berita dari News API dengan error handling dan retry otomatis """
    url = "https://newsapi.org/v2/everything"
    params = {
        "q": "latest",
        "sortBy": "publishedAt",
        "apiKey": NEWS_API_KEY,
        "pageSize": 100
    }
    
    retries = 3  # Coba ulang 3 kali jika gagal
    for attempt in range(retries):
        response = requests.get(url, params=params)
        
        if response.status_code == 200:
            logging.info("Berhasil fetch berita dari News API")
            return response.json().get("articles", [])
        
        logging.warning(f"Gagal fetch berita (percobaan {attempt+1}/{retries}), status: {response.status_code}")
        time.sleep(2)  # Tunggu 2 detik sebelum mencoba lagi
    
    logging.error("Gagal mendapatkan berita setelah beberapa kali percobaan.")
    return []

@cache.cached(timeout=600, key_prefix='cached_news')  # Cache selama 10 menit
def get_cached_news():
    return fetch_news()

def filter_news(articles, preferences, interactions):
    """ Filter berita berdasarkan preferences dan interactions """
    filtered_news = []
    
    for article in articles:
        title = article.get("title", "").lower()
        description = article.get("description", "").lower()
        relevance_score = 0

        # Cek kecocokan dengan preferences user
        for pref in preferences:
            if re.search(rf"\b{re.escape(pref)}\b", title) or re.search(rf"\b{re.escape(pref)}\b", description):
                relevance_score += 1

        # Cek interaksi sebelumnya
        for interaction in interactions:
            if re.search(rf"\b{re.escape(interaction)}\b", title) or re.search(rf"\b{re.escape(interaction)}\b", description):
                relevance_score += 2  # Bobot lebih besar jika pernah berinteraksi

        if relevance_score > 0:
            filtered_news.append((relevance_score, article))

    # Urutkan berdasarkan skor relevansi
    filtered_news.sort(reverse=True, key=lambda x: x[0])
    return [news[1] for news in filtered_news[:15]]  # Ambil 15 berita terbaik

@app.route("/recommend_news", methods=["POST"])
def recommend_news():
    data = request.json
    preferences = data.get("preferences", [])
    interactions = data.get("user_interactions", [])

    all_news = get_cached_news()
    if not all_news:
        return jsonify({"error": "Gagal mengambil berita. Coba lagi nanti."}), 500

    recommended_news = filter_news(all_news, preferences, interactions)
    return jsonify(recommended_news)

if __name__ == "__main__":
    app.run(debug=True)
