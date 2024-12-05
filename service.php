<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Page</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your stylesheet here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(2px);
            color: white;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        .section {
            margin-bottom: 40px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            color: #4CAF50;
        }
        .section img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px 0;
        }
        .solutions ul {
            list-style: disc;
            padding-left: 20px;
        }
        .solutions ul li {
            margin-bottom: 10px;
        }
        .animation {
            text-align: center;
            margin: 20px 0;
        }
        .animation img {
            width: 300px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px);
            }
            60% {
                transform: translateY(-15px);
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Our Services</h1>
        <p>Making Rental Processes Easier for Bachelors and Employers</p>
    </div>

    <div class="container">
        <div class="section">
            <h2>Description of the Problem</h2>
            <p>Providing an online platform especially for renting out employers' and bachelor students' homes is the goal of this project. Advanced features on the platform include the ability to search for shared accommodations, personalize property filters, and schedule extra services like housekeeping and maid assistance. The main problem being addressed is the lack of a centralized, well-organized platform that makes it easy for bachelors to locate suitable and reasonably priced homes. Our platform aims to eliminate these challenges and ensure transparency for both renters and property owners.</p>
        </div>

        <div class="section">
            <h2>Review of Existing Systems</h2>
            <ul>
                <li><strong>Bdproperty.com:</strong> Not customized for bachelors; limited house visualization features.</li>
                <li><strong>THE TOLET.COM:</strong> Improperly organized UI, no location picking, no filtering options.</li>
                <li><strong>VARADeal.Com:</strong> Improperly organized UI, lacks customization for bachelors, poor house visualization.</li>
            </ul>
            <img src="re.png" alt="Comparison of existing systems">
        </div>

        <div class="section solutions">
            <h2>Solutions Adopted</h2>
            <ul>
                <li><strong>User-Centric Interface:</strong> Simplified navigation and filtering options for location, rent, and services.</li>
                <li><strong>Enhanced Search Features:</strong> Refined searches based on pricing, shared accommodations, and accessibility.</li>
                <li><strong>Integrated Housekeeping Service:</strong> Option to book housekeeping services.</li>
                <li><strong>Effective Property Listing System:</strong> Simplified uploads of property details, pricing, and availability.</li>
                <li><strong>Verification and Quality Assurance:</strong> Property and user verification for listing authenticity.</li>
                <li><strong>Scalable Architecture:</strong> Built for future growth to include more cities and features.</li>
            </ul>
            <div class="animation">
                <img src="ver.jpg" alt="Animated feature icon">
            </div>
        </div>

        <div class="section">
            <h2>Conclusion</h2>
            <p>Our platform strives to remove barriers for bachelors and employers in finding rental properties. By addressing discrimination and creating a seamless interface, we hope to redefine the rental experience in Dhaka and beyond.</p>
        </div>
    </div>
</body>
</html>
