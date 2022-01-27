-include .env.local

run:
	php -S localhost:8000
.PHONY: run

ngrok:
	ngrok http 8000
.PHONY: ngrok