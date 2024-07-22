<!DOCTYPE html>
<html>
<head>
    <title>Email Sender</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h3>Wallet Balance: <span id="wallet-balance"></span></h3>
    <form id="emailForm">
        <input type="text" name="emails[]" placeholder="Email 1" required><br>
        <input type="text" name="emails[]" placeholder="Email 2" required><br>
        <textarea name="message" placeholder="Message" required></textarea><br>
        <button type="submit">Send Emails</button>
    </form>
    <p id="total-cost"></p>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            async function fetchWalletBalance() {
                const response = await fetch('/api/wallet-balance', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer yourAccessToken', // Replace with actual token
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const data = await response.json();
                document.getElementById('wallet-balance').textContent = data.balance;
            }

            async function sendEmails(emails, message) {
                const response = await fetch('/api/send-emails', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer yourAccessToken', // Replace with actual token
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        emails: emails,
                        message: message
                    })
                });

                const data = await response.json();
                alert(data.message);
                if (data.new_balance !== undefined) {
                    document.getElementById('wallet-balance').textContent = data.new_balance;
                }
            }

            document.getElementById('emailForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const emails = formData.getAll('emails[]');
                const message = formData.get('message');

                const totalCost = emails.length * 1.10;
                document.getElementById('total-cost').textContent = `Total cost: ${totalCost} INR`;

                if (confirm(`Are you sure you want to send ${emails.length} emails for a total cost of ${totalCost} INR?`)) {
                    sendEmails(emails, message);
                }
            });

            fetchWalletBalance();
        });
    </script>
</body>
</html>
