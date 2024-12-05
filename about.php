<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meet Our Team</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        /* Section Styles */
        .team-section {
            text-align: center;
            padding: 50px;
        }

        .title {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .description {
            font-size: 1rem;
            color: #666;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Team Grid Styles */
        .team-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .team-member {
            background: linear-gradient(145deg, #D29E9EFF, #FEFEFFFF);
            border-radius: 15px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 200px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            opacity: 0;
            transform: translateY(50px);
        }

        .team-member:hover {
            transform: scale(1.05);
            box-shadow: 8px 8px 20px rgba(0, 0, 0, 0.2);
        }

        .team-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        h3 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 5px;
        }

        p {
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>
<body>
    <section class="team-section">
        <h1 class="title">Meet Our Team</h1>
        <p class="description">
            We believe in our team embodying this principle in every way. Our members hail from different walks of life, bringing unique perspectives, skills, and experiences to the table.
        </p>
        <div class="team-grid">
            <div class="team-member">
                <img src="moyen.jpg" alt="Olivia Wilson" class="team-photo">
                <h3>Ishtiak Ahmed Moyen</h3>
                <p>Back-End Developer</p>
                <p>Team Manager</p>
            </div>
            <div class="team-member">
                <img src="arnob.jpg" alt="Francois Mercer" class="team-photo">
                <h3>Arnob Ahmed</h3>
                <p>Database Management</p>
            </div>
            <div class="team-member">
                <img src="Isnat_Hossain_Rijon.jpg" alt="Harper Russo" class="team-photo">
                <h3>Isnat Hossain Rijon</h3>
                <p>Front-End Developer</p>
            </div>
           
        </div>
    </section>
    <script>
        // Add fade-in effect on scroll
        const teamMembers = document.querySelectorAll('.team-member');

        const revealOnScroll = () => {
            const windowHeight = window.innerHeight;

            teamMembers.forEach((member, index) => {
                const rect = member.getBoundingClientRect();
                if (rect.top < windowHeight) {
                    member.style.opacity = 1;
                    member.style.transition = `opacity 0.6s ease-out ${index * 0.2}s, transform 0.6s ease-out ${index * 0.2}s`;
                    member.style.transform = 'translateY(0)';
                }
            });
        };

        window.addEventListener('scroll', revealOnScroll);
        window.addEventListener('load', revealOnScroll);
    </script>
</body>
</html>
