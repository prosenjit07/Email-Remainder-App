#!/bin/bash

echo "🧪 Testing Laravel Todo Reminder Application"
echo "============================================="

# Check if server is running
echo "1. Checking if server is running..."
if curl -s http://localhost:8000 > /dev/null; then
    echo "✅ Server is running on http://localhost:8000"
else
    echo "❌ Server is not running. Start it with: php artisan serve"
    exit 1
fi

# Test API endpoint
echo "2. Testing API endpoint..."
if curl -s -H "Accept: application/json" http://localhost:8000/api/todos > /dev/null; then
    echo "✅ API endpoint is working"
    TODO_COUNT=$(curl -s -H "Accept: application/json" http://localhost:8000/api/todos | grep -o '"id":' | wc -l)
    echo "📊 Found $TODO_COUNT todos in database"
else
    echo "❌ API endpoint is not working"
fi

# Test frontend
echo "3. Testing frontend..."
if curl -s http://localhost:8000/todos | grep -q "Todo List Manager"; then
    echo "✅ Frontend is accessible"
else
    echo "❌ Frontend is not accessible"
fi

# Test email logs
echo "4. Testing email logs..."
if curl -s http://localhost:8000/email-logs | grep -q "Email Logs"; then
    echo "✅ Email logs page is accessible"
else
    echo "❌ Email logs page is not accessible"
fi

# Check queue status
echo "5. Checking queue status..."
if php artisan queue:work --once > /dev/null 2>&1; then
    echo "✅ Queue system is working"
else
    echo "⚠️  Queue system may need attention"
fi

echo ""
echo "🎉 Application Status:"
echo "====================="
echo "📱 Frontend: http://localhost:8000/todos"
echo "📊 Email Logs: http://localhost:8000/email-logs"
echo "🔌 API: http://localhost:8000/api/todos"
echo ""
echo "💡 To start the application:"
echo "   1. php artisan serve"
echo "   2. php artisan queue:work (in another terminal)"
echo "   3. php artisan schedule:work (in another terminal)"
echo ""
echo "🧪 To test email reminders:"
echo "   php artisan todos:test-reminder" 