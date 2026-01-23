#!/bin/bash
# WPGraphQL Gravity Forms - Ralph Loop
# Usage: ./loop.sh [plan] [max_iterations]
# Examples:
#   ./loop.sh              # Build mode, unlimited
#   ./loop.sh 20           # Build mode, max 20 tasks
#   ./loop.sh plan         # Plan mode, unlimited
#   ./loop.sh plan 5       # Plan mode, max 5 tasks

set -euo pipefail

# Parse arguments
if [ $# -eq 0 ]; then
    # No arguments - Build mode, unlimited
    MODE="build"
    PROMPT_FILE="PROMPT_build.md"
    MAX_ITERATIONS=0
elif [ "$1" = "plan" ]; then
    # Plan mode
    MODE="plan"
    PROMPT_FILE="PROMPT_plan.md"
    MAX_ITERATIONS=${2:-0}
elif [[ "$1" =~ ^[0-9]+$ ]]; then
    # Build mode with max tasks
    MODE="build"
    PROMPT_FILE="PROMPT_build.md"
    MAX_ITERATIONS=$1
fi

ITERATION=0
CURRENT_BRANCH=$(git branch --show-current)

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Ralph Loop: WPGraphQL Gravity Forms"
echo "Mode:   $MODE"
echo "Prompt: $PROMPT_FILE"
echo "Branch: $CURRENT_BRANCH"
[ $MAX_ITERATIONS -gt 0 ] && echo "Max:    $MAX_ITERATIONS iterations"
echo ""
echo "🎯 MONITORING: Sessions auto-shared for visibility"
echo "   Run './monitor.sh' in another terminal for live status"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Verify prompt file exists
if [ ! -f "$PROMPT_FILE" ]; then
    echo "Error: $PROMPT_FILE not found"
    echo "Create it from the template in docs/ or see Ralph playbook"
    exit 1
fi

# Verify required files exist
if [ ! -f "AGENTS.md" ]; then
    echo "Warning: AGENTS.md not found - Ralph won't know how to run tests"
fi

if [ ! -f "IMPLEMENTATION_PLAN.md" ] && [ "$MODE" = "build" ]; then
    echo "Warning: IMPLEMENTATION_PLAN.md not found"
    echo "Run in plan mode first: ./loop.sh plan"
    exit 1
fi

CURRENT_DIR=$(pwd)

while true; do
    if [ $MAX_ITERATIONS -gt 0 ] && [ $ITERATION -ge $MAX_ITERATIONS ]; then
        echo "Reached max iterations: $MAX_ITERATIONS"
        break
    fi

    # Run Ralph iteration with selected prompt
    # --model: Model to use in provider/model format
    # --format: json for structured output
    # --share: Auto-share session for visibility (like ghuntley recommends)
    # --verbose: Detailed execution logging for monitoring
    # Note: opencode run takes prompt via --file flag

    SESSION_TITLE="Ralph-$MODE-Iteration-$ITERATION"
    echo "🔗 Starting iteration $ITERATION - watch for shareable URL..."

    # Use absolute path for the prompt file
    PROMPT_FILE_ABS="$CURRENT_DIR/$PROMPT_FILE"
    echo "Using prompt file: $PROMPT_FILE_ABS"
    opencode run "Execute the following ralph loop: $( cat $PROMPT_FILE_ABS ) " \
        --model "opencode/grok-code" \
        --format json \
        --title "$SESSION_TITLE"

    # Push changes after each iteration
    git push origin "$CURRENT_BRANCH" || {
        echo "Failed to push. Creating remote branch..."
        git push -u origin "$CURRENT_BRANCH"
    }

    ITERATION=$((ITERATION + 1))
    echo -e "\n\n======================== LOOP $ITERATION ========================\n"
done

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Ralph loop complete"
echo "Branch: $CURRENT_BRANCH"
echo "Iterations: $ITERATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
