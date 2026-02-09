#!/bin/bash

# TrustFolio Deployment Script
# Usage: ./deploy.sh [--skip-build] [--migrate] [--help]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Default values
SKIP_BUILD=false
SKIP_MIGRATE=false
ENV_FILE=".env.production"

# Help function
show_help() {
    echo -e "${GREEN}TrustFolio Deployment Script${NC}"
    echo ""
    echo "Usage: ./deploy.sh [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  --skip-build    Skip npm build step"
    echo "  --skip-migrate  Skip database migrations"
    echo "  --help          Show this help message"
    echo ""
    echo "Environment variables required in .env.production:"
    echo "  APP_NAME, APP_ENV, APP_DEBUG, APP_URL"
    echo "  DB_CONNECTION, DB_DATABASE, DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD"
    echo "  TELEGRAM_BOT_TOKEN"
    echo "  STRIPE_KEY, STRIPE_SECRET, STRIPE_WEBHOOK_SECRET"
    echo "  STRIPE_MONTHLY_PRICE_ID, STRIPE_ANNUAL_PRICE_ID"
    echo ""
}

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        --skip-build)
            SKIP_BUILD=true
            shift
            ;;
        --skip-migrate)
            SKIP_MIGRATE=true
            shift
            ;;
        --help|-h)
            show_help
            exit 0
            ;;
        *)
            echo -e "${RED}Unknown option: $1${NC}"
            show_help
            exit 1
            ;;
    esac
done

# Get script directory
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo -e "${GREEN}🚀 Starting TrustFolio deployment...${NC}"
echo ""

# Step 1: Check for .env.production
echo -e "${YELLOW}1. Checking environment configuration...${NC}"
if [ ! -f "$ENV_FILE" ]; then
    echo -e "${RED}✗ $ENV_FILE not found!${NC}"
    echo "Please create $ENV_FILE with your production settings."
    echo "Copy .env.example as a template:"
    echo "  cp .env.example $ENV_FILE"
    exit 1
fi
echo -e "${GREEN}✓ Environment file found${NC}"

# Step 2: Install dependencies
echo ""
echo -e "${YELLOW}2. Installing PHP dependencies...${NC}"
composer install --no-dev --optimize-autoloader

# Step 3: Build assets (unless skipped)
if [ "$SKIP_BUILD" = false ]; then
    echo ""
    echo -e "${YELLOW}3. Building frontend assets...${NC}"
    npm ci
    npm run build
else
    echo ""
    echo -e "${YELLOW}3. Skipping asset build (--skip-build)${NC}"
fi

# Step 4: Run migrations (unless skipped)
if [ "$SKIP_MIGRATE" = false ]; then
    echo ""
    echo -e "${YELLOW}4. Running database migrations...${NC}"
    php artisan migrate --force --env=$ENV_FILE
else
    echo ""
    echo -e "${YELLOW}4. Skipping migrations (--skip-migrate)${NC}"
fi

# Step 5: Clear and cache routes
echo ""
echo -e "${YELLOW}5. Caching routes and config...${NC}"
php artisan route:cache --env=$ENV_FILE
php artisan config:cache --env=$ENV_FILE

# Step 6: Optimize
echo ""
echo -e "${YELLOW}6. Optimizing application...${NC}"
php artisan optimize --env=$ENV_FILE

# Step 7: Set permissions
echo ""
echo -e "${YELLOW}7. Setting storage permissions...${NC}"
chmod -R 775 storage bootstrap/cache

echo ""
echo -e "${GREEN}✅ Deployment complete!${NC}"
echo ""
echo "Next steps:"
echo "  1. Configure your web server (Nginx/Apache) to point to $SCRIPT_DIR/public"
echo "  2. Ensure HTTPS is enabled in production"
echo "  3. Set up Stripe webhook endpoint: https://yourdomain.com/webhook/stripe"
echo ""
