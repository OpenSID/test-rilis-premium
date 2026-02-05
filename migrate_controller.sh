#!/bin/bash

#
# OpenSID Controller Migration Script
# Script untuk membantu batch migration controllers dari CI3 ke Laravel
#
# Usage: bash migrate_controller.sh ControllerName
#

set -e

CONTROLLER_NAME="${1:-Sms}"
CONTROLLER_FILE="donjo-app/controllers/${CONTROLLER_NAME}.php"
LARAVEL_CONTROLLER_FILE="app/Http/Controllers/${CONTROLLER_NAME}Controller.php"
ROUTES_FILE="routes/$(echo ${CONTROLLER_NAME,,}).php"

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}=== OpenSID Controller Migration Script ===${NC}\n"

# Check if CI3 controller exists
if [ ! -f "$CONTROLLER_FILE" ]; then
    echo -e "${RED}❌ Error: $CONTROLLER_FILE not found${NC}"
    exit 1
fi

echo -e "${GREEN}✓${NC} Found CI3 controller: $CONTROLLER_FILE"

# Step 1: Check Laravel structure
echo -e "\n${YELLOW}Step 1: Checking Laravel directory structure...${NC}"

mkdir -p "app/Http/Controllers" "app/Http/Requests" "app/Http/Middleware" "routes"
echo -e "${GREEN}✓${NC} Directories ready"

# Step 2: Generate Laravel controller
echo -e "\n${YELLOW}Step 2: Analyzing CI3 controller...${NC}"

# Extract class name and methods
CLASS_NAME="${CONTROLLER_NAME}Controller"
METHOD_COUNT=$(grep -c "public function" "$CONTROLLER_FILE" || echo 0)
USES_INPUT=$(grep -c "\$this->input" "$CONTROLLER_FILE" || echo 0)
USES_SESSION=$(grep -c "\$this->session" "$CONTROLLER_FILE" || echo 0)
USES_REDIRECT=$(grep -c "redirect_with\|redirect(" "$CONTROLLER_FILE" || echo 0)

echo -e "${GREEN}✓${NC} Analysis complete:"
echo "   - Methods: $METHOD_COUNT"
echo "   - Uses \$this->input: $USES_INPUT times"
echo "   - Uses \$this->session: $USES_SESSION times"
echo "   - Uses redirect: $USES_REDIRECT times"

# Step 3: Create checklist
echo -e "\n${YELLOW}Step 3: Migration checklist${NC}"

cat << 'EOF' > "migration_${CONTROLLER_NAME}.md"
# Migration Checklist for ${CONTROLLER_NAME}Controller

## Before Migration
- [ ] Read MIGRATION_GUIDE.md
- [ ] Backup CI3 controller
- [ ] Create feature branch

## Code Conversion
- [ ] Replace class declaration
- [ ] Update namespace
- [ ] Replace $this->input calls
- [ ] Replace $this->session calls
- [ ] Replace show_404() calls
- [ ] Update redirect patterns
- [ ] Update route URL generation
- [ ] Add method type hints
- [ ] Remove CI3 specific code

## Testing
- [ ] Create Form Request
- [ ] Write feature tests
- [ ] Write unit tests
- [ ] Manual testing in browser
- [ ] Test all CRUD operations

## Integration
- [ ] Create routes file
- [ ] Register routes in web.php
- [ ] Test with old CI3 routes
- [ ] Compare results

## Documentation
- [ ] Update API docs
- [ ] Add controller comments
- [ ] Document breaking changes

## Deployment
- [ ] Code review
- [ ] Deploy to staging
- [ ] QA approval
- [ ] Deploy to production
EOF

echo "   - Checklist created: migration_${CONTROLLER_NAME}.md"

# Step 4: Show next steps
echo -e "\n${YELLOW}Step 4: Next steps${NC}"

cat << EOF
${GREEN}Manual tasks:${NC}

1. Review CI3 controller:
   cat $CONTROLLER_FILE

2. Create new Laravel controller:
   php artisan make:controller ${CLASS_NAME}

3. Copy and refactor methods:
   # Use MIGRATION_GUIDE.md as reference
   # Common replacements:
   #   \$this->input       → \$request
   #   redirect_with()    → redirect()->with()
   #   ci_route()         → route()
   #   show_404()         → abort(404)

4. Create routes:
   vim $ROUTES_FILE

5. Create Form Requests:
   php artisan make:request Store${CONTROLLER_NAME}Request

6. Create tests:
   php artisan make:test ${CLASS_NAME}Test --feature

7. Register in routes/web.php:
   require base_path('routes/$(echo ${CONTROLLER_NAME,,}).php');

${YELLOW}Resources:${NC}
- Migration Guide: MIGRATION_GUIDE.md
- Roadmap: MIGRATION_ROADMAP.md
- POC Example: app/Http/Controllers/SmsController.php
EOF

echo -e "\n${GREEN}✓${NC} Preparation complete!"
echo -e "Open migration_${CONTROLLER_NAME}.md and start refactoring\n"
