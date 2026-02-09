<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviewBridge Widget Test Page</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
            background: #f5f5f5;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .test-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .test-section h2 {
            color: #4f46e5;
            margin-top: 0;
            font-size: 18px;
        }
        .widget-container {
            min-height: 100px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafafa;
        }
        .widget-container:has(.reviewbridge-widget) {
            border-color: #4f46e5;
            background: white;
        }
        #console-output {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
        }
        .log-entry {
            margin: 5px 0;
            padding: 2px 0;
        }
        .log-entry.error {
            color: #f87171;
        }
        .log-entry.success {
            color: #4ade80;
        }
        .log-entry.info {
            color: #60a5fa;
        }
        .instructions {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .instructions h3 {
            margin-top: 0;
            color: #92400e;
        }
        .instructions ol {
            margin: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 5px 0;
            color: #78350f;
        }
    </style>
</head>
<body>
    <h1>ReviewBridge Widget Test Page</h1>

    <div class="instructions">
        <h3>Test Instructions</h3>
        <ol>
            <li>Register a new creator account at <a href="{{ url('/register') }}" target="_blank">{{ url('/register') }}</a></li>
            <li>Copy the embed code from the Creator Settings page</li>
            <li>Paste the embed code below and test the widget</li>
            <li>Check the browser console for errors</li>
        </ol>
    </div>

    <div class="test-section">
        <h2>Test 1: Default Widget Container</h2>
        <p>Default container with data-collection attribute:</p>
        <div class="widget-container">
            <!-- Paste embed code here -->
            <p style="color: #999;">Paste your embed code below:</p>
        </div>
    </div>

    <div class="test-section">
        <h2>Test 2: Multiple Widget Containers</h2>
        <p>Test multiple containers on the same page:</p>
        <div class="widget-container" id="test-widget-1"></div>
        <div style="height: 20px;"></div>
        <div class="widget-container" id="test-widget-2"></div>
    </div>

    <div class="test-section">
        <h2>Console Output</h2>
        <div id="console-output">
            <div class="log-entry info">Widget test page loaded...</div>
            <div class="log-entry info">Waiting for widget script to load...</div>
        </div>
    </div>

    <script>
        // Capture console output
        const consoleOutput = document.getElementById('console-output');
        const originalLog = console.log;
        const originalError = console.error;

        function addLog(message, type = 'info') {
            const entry = document.createElement('div');
            entry.className = `log-entry ${type}`;
            entry.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
            consoleOutput.appendChild(entry);
            consoleOutput.scrollTop = consoleOutput.scrollHeight;
        }

        console.log = function(...args) {
            originalLog.apply(console, args);
            addLog(args.join(' '), 'success');
        };

        console.error = function(...args) {
            originalError.apply(console, args);
            addLog(args.join(' '), 'error');
        };

        // Monitor for widget loading
        function monitorWidget() {
            const widget = document.querySelector('.reviewbridge-widget');
            if (widget) {
                addLog('Widget detected in DOM!', 'success');
                addLog(`Widget has ${widget.querySelectorAll('.reviewbridge-card').length} review cards`, 'success');
            } else {
                addLog('No widget detected yet...', 'info');
            }
        }

        // Check periodically
        setInterval(monitorWidget, 1000);

        // Listen for script load
        window.addEventListener('load', () => {
            addLog('Page fully loaded', 'success');
            monitorWidget();
        });

        // Global function to inject widget for testing
        window.testWidget = function(collectionUrl) {
            const container = document.querySelector('.widget-container');
            if (container) {
                container.innerHTML = '';
                const script = document.createElement('script');
                script.src = `{{ url('/embed') }}/${collectionUrl}`;
                script.defer = true;
                script.onload = () => addLog('Script loaded successfully', 'success');
                script.onerror = () => addLog('Script failed to load', 'error');
                container.appendChild(script);
                addLog(`Injecting widget for collection: ${collectionUrl}`, 'info');
            }
        };

        addLog('Test page ready. Use testWidget("collection-url") to inject a widget.', 'info');
    </script>
</body>
</html>
