#!/bin/bash
set -e

echo "🔧 Cleaning old build artifacts..."
rm -rf js dist

# Ensure package-lock.json exists (required for npm ci)
if [ ! -f package-lock.json ]; then
    echo "❌ package-lock.json not found."
    echo "Run 'npm install' once to generate it."
    exit 1
fi

echo "📦 Installing Node dependencies (npm ci)..."
npm ci

echo "⚡ Building frontend with Vite..."
npm run build

# Validate build output
if [ ! -d js ]; then
    echo "❌ Build failed: 'js/' directory not generated."
    exit 1
fi

if [ ! -d css ]; then
    echo "❌ Build failed: 'css/' directory not generated."
    exit 1
fi

echo "✅ Build completed successfully!"
echo "Generated folders:"
echo " - js/"
echo " - css/"
echo ""
echo "You can now run: ./package.sh <version> to create a release ZIP."
