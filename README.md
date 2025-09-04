# Bit & Bait Website

## Setup
Install dependencies
```
composer install
```

## Configuration
Some configurations are set in `.env` file as describe below:
- Create a folder for Wordpress installation in your project root (We name it `cms`)
- Add `cms` into `.gitignore` because Wordpress installation contains too many files and folders (10k++)
- Set `app.baseURL` and `wordpress_url` as you need.
