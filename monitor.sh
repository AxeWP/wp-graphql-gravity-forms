#!/bin/bash
# Ralph Loop Monitor
# Run this in a separate terminal to watch Ralph loop progress
# Usage: ./monitor.sh

echo "🎯 Ralph Loop Monitor Started"
echo "Watching for session updates..."
echo ""
echo "💡 TIP: Look for 'https://opncd.ai/share/...' URLs in Ralph output for live viewing"
echo ""

while true; do
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "$(date '+%H:%M:%S') - Session Status"
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

    # List recent sessions
    echo "Recent Sessions:"
    opencode session list --max-count 5 2>/dev/null || echo "No sessions found yet"

    echo ""
    echo "Session Stats:"
    opencode stats --days 1 2>/dev/null || echo "No stats available yet"

    echo ""
    echo "Git Status:"
    git status --porcelain | head -10

    echo ""
    echo "Press Ctrl+C to stop monitoring"
    sleep 30  # Check every 30 seconds
done
