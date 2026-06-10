#!/bin/bash
set -e

APP_ID="dailyverses"

##############################################
# 0. VEILIGHEIDSCHECKS
##############################################

# Check: werkdirectory moet schoon zijn
if [ -n "$(git status --porcelain)" ]; then
    echo "❌ Working tree is not clean."
    echo "   Commit, stash of revert je wijzigingen voordat je een release draait."
    exit 1
fi

# Check: branch mag niet diverged zijn t.o.v. origin/main
git fetch origin >/dev/null 2>&1

DIVERGENCE=$(git rev-list --left-right --count origin/main...HEAD | awk '{print $1 " " $2}')
BEHIND=$(echo "$DIVERGENCE" | awk '{print $1}')
AHEAD=$(echo "$DIVERGENCE" | awk '{print $2}')

if [ "$AHEAD" != "0" ] || [ "$BEHIND" != "0" ]; then
    echo "❌ Branch 'main' is diverged t.o.v. 'origin/main'."
    echo "   Los dit eerst op (bijv. via 'git pull --rebase' of 'git reset --hard origin/main')."
    exit 1
fi

##############################################
# 1. INTERACTIEVE CONVENTIONAL COMMITS PROMPT
##############################################
prompt_for_commit_message() {
    echo "Select commit type:"
    echo "1) feat      – nieuwe functionaliteit"
    echo "2) fix       – bugfix"
    echo "3) docs      – documentatie"
    echo "4) refactor  – code herschikking"
    echo "5) style     – formatting"
    echo "6) test      – tests"
    echo "7) chore     – onderhoud / scripts"
    echo "8) build     – build systeem"
    echo "9) ci        – pipelines / GitHub Actions"
    echo ""

    read -p "Choose type (1-9): " TYPE_CHOICE

    case $TYPE_CHOICE in
        1) TYPE="feat" ;;
        2) TYPE="fix" ;;
        3) TYPE="docs" ;;
        4) TYPE="refactor" ;;
        5) TYPE="style" ;;
        6) TYPE="test" ;;
        7) TYPE="chore" ;;
        8) TYPE="build" ;;
        9) TYPE="ci" ;;
        *) echo "Invalid choice"; exit 1 ;;
    esac

    echo ""
    read -p "Enter commit subject: " SUBJECT

    if [ -z "$SUBJECT" ]; then
        echo "Subject cannot be empty."
        exit 1
    fi

    COMMIT_MSG="${TYPE}: ${SUBJECT}"
}

##############################################
# 2. UPDATE VERSION IN appinfo/info.xml
##############################################
update_info_xml_version() {
    local new_version="$1"
    local file="appinfo/info.xml"

    echo "Updating appinfo/info.xml to version ${new_version}"

    sed -i "s|<version>.*</version>|<version>${new_version}</version>|" "$file"
}

##############################################
# 2a UPDATE VERSION IN package.json
##############################################
update_package_json_version() {
    local new_version="$1"
    local file="package.json"

    echo "Updating package.json to version ${new_version}"

    # Alleen de "version": "..." regel vervangen
    sed -i "s/\"version\": \".*\"/\"version\": \"${new_version}\"/" "$file"
}

##############################################
# 3. AUTOMATISCHE CHANGELOG GENERATOR
##############################################
generate_changelog() {
    local last_tag="$1"
    local new_version="$2"

    echo "Generating changelog (Conventional Commits)..."

    if [ -z "$last_tag" ]; then
        RANGE=""
    else
        RANGE="${last_tag}..HEAD"
    fi

    FEAT=$(git log $RANGE --pretty=format:"- %s" | grep "^feat" || true)
    FIX=$(git log $RANGE --pretty=format:"- %s" | grep "^fix" || true)
    REFACTOR=$(git log $RANGE --pretty=format:"- %s" | grep "^refactor" || true)
    DOCS=$(git log $RANGE --pretty=format:"- %s" | grep "^docs" || true)
    CHORE=$(git log $RANGE --pretty=format:"- %s" | grep "^chore" || true)
    BUILD=$(git log $RANGE --pretty=format:"- %s" | grep "^build" || true)
    CI=$(git log $RANGE --pretty=format:"- %s" | grep "^ci" || true)

    {
        echo "## v${new_version} ($(date +%Y-%m-%d))"
        echo ""

        [ -n "$FEAT" ] && echo "### ✨ Features" && echo "$FEAT" && echo ""
        [ -n "$FIX" ] && echo "### 🐛 Fixes" && echo "$FIX" && echo ""
        [ -n "$REFACTOR" ] && echo "### 🔧 Refactors" && echo "$REFACTOR" && echo ""
        [ -n "$DOCS" ] && echo "### 📚 Documentation" && echo "$DOCS" && echo ""
        [ -n "$CHORE" ] && echo "### 🧹 Chores" && echo "$CHORE" && echo ""
        [ -n "$BUILD" ] && echo "### 🏗️ Build" && echo "$BUILD" && echo ""
        [ -n "$CI" ] && echo "### 🔁 CI" && echo "$CI" && echo ""

        echo ""
    } > CHANGELOG_NEW.md
}

##############################################
# 4. INTERACTIEVE COMMIT VOOR DE RELEASE
##############################################
echo "🔧 Preparing commit..."
prompt_for_commit_message

git add .
if git diff --cached --quiet; then
    echo "❌ Geen wijzigingen om te committen voor de release."
    exit 1
fi

git commit -m "${COMMIT_MSG}"
git push


##############################################
# 4. RELEASE VERSION DETECTIE + AUTO TAG
##############################################
BASE_VERSION="$1"

if [ -z "$BASE_VERSION" ]; then
    echo "No base version supplied."
    echo "Usage: ./release.sh 1.0"
    exit 1
fi

CURRENT_XML_VERSION=$(grep -oPm1 "(?<=<version>)[^<]+" appinfo/info.xml)
echo "Version in info.xml: ${CURRENT_XML_VERSION}"

echo "Detecting latest tag for ${BASE_VERSION}.x ..."

LATEST_TAG=$(git tag -l "v${BASE_VERSION}.*" | sort -V | tail -n 1)

if [ -z "$LATEST_TAG" ]; then
    echo "No tags found for ${BASE_VERSION}.x"

    if [[ "${CURRENT_XML_VERSION}" == ${BASE_VERSION}.* ]]; then
        echo "Creating initial tag v${CURRENT_XML_VERSION} (from info.xml)"
        git tag -a "v${CURRENT_XML_VERSION}" -m "Initial version ${CURRENT_XML_VERSION}"
        git push origin "v${CURRENT_XML_VERSION}"
        LATEST_TAG="v${CURRENT_XML_VERSION}"
    else
        echo "info.xml version (${CURRENT_XML_VERSION}) does not match base version (${BASE_VERSION})"
        exit 1
    fi
fi

echo "Latest tag found: ${LATEST_TAG}"

PATCH=$(echo "$LATEST_TAG" | sed -E "s/v${BASE_VERSION}\.([0-9]+)/\1/")
PATCH=$((PATCH + 1))

NEW_VERSION="${BASE_VERSION}.${PATCH}"

echo "New version will be: v${NEW_VERSION}"

##############################################
# 6. UPDATE info.xml + package.json VERSION + COMMIT
##############################################
update_info_xml_version "${NEW_VERSION}"
update_package_json_version "${NEW_VERSION}"

git add appinfo/info.xml package.json
git commit -m "chore: bump version to ${NEW_VERSION}"
git push

##############################################
# 7. BUILD + PACKAGE
##############################################
echo "Running build..."
./build.sh

echo "Packaging..."
./package.sh "${NEW_VERSION}"

ZIP_NAME="${APP_ID}-${NEW_VERSION}.zip"
TAR_NAME="${APP_ID}-${NEW_VERSION}.tar.gz"

if [ ! -f "${ZIP_NAME}" ]; then
    echo "❌ ZIP file not found: ${ZIP_NAME}"
    exit 1
fi

if [ ! -f "${TAR_NAME}" ]; then
    echo "❌ TAR.GZ file not found: ${TAR_NAME}"
    exit 1
fi


##############################################
# 8. CHANGELOG GENEREREN
##############################################
generate_changelog "${LATEST_TAG}" "${NEW_VERSION}"

##############################################
# 9. TAGGEN + RELEASE
##############################################
echo "Creating Git tag v${NEW_VERSION}..."
git tag -a "v${NEW_VERSION}" -m "Release v${NEW_VERSION}"
git push origin "v${NEW_VERSION}"

echo "Creating GitHub Release..."
gh release create "v${NEW_VERSION}" \
  "${ZIP_NAME}" \
  "${TAR_NAME}" \
  --title "v${NEW_VERSION}" \
  --notes-file "CHANGELOG_NEW.md"

##############################################
# 10. CHANGELOG UPDATEN IN DE REPO
##############################################
echo "Updating CHANGELOG.md..."
if [ -f CHANGELOG.md ]; then
    cat CHANGELOG.md >> CHANGELOG_NEW.md
fi
mv CHANGELOG_NEW.md CHANGELOG.md
git add CHANGELOG.md
git commit -m "docs: update changelog for v${NEW_VERSION}"
git push

echo "Release v${NEW_VERSION} published successfully!"
