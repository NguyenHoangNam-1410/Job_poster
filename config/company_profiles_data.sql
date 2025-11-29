-- ============================================================================
-- Company Profiles Data for AI Assistant
-- This file contains detailed company information to enhance AI responses
-- ============================================================================

USE job_poster;

-- ============================================================================
-- Step 1: Create COMPANY_PROFILES table for detailed company information
-- ============================================================================

CREATE TABLE IF NOT EXISTS `COMPANY_PROFILES` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employer_id` INT NOT NULL,
    `company_name` VARCHAR(255) NOT NULL,
    `industry` VARCHAR(100),
    `founded_year` INT,
    `headquarters` VARCHAR(255),
    `employee_count` VARCHAR(50),
    `company_type` VARCHAR(50),
    `about_us` TEXT,
    `company_culture` TEXT,
    `core_values` TEXT,
    `products_services` TEXT,
    `technologies` TEXT,
    `benefits` TEXT,
    `awards_recognition` TEXT,
    `social_links` TEXT,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`employer_id`) REFERENCES `EMPLOYERS`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_employer_id` (`employer_id`),
    INDEX `idx_industry` (`industry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Step 2: Insert sample company profiles with detailed information
-- ============================================================================

-- Company 1: EdTech Solutions - Educational Technology Company
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    1, -- Update with actual employer_id from your database
    'EdTech Solutions',
    'Education Technology',
    2018,
    'Ho Chi Minh City, Vietnam',
    '50-100 employees',
    'Technology',
    'EdTech Solutions is a leading educational technology company in Southeast Asia, dedicated to transforming how people learn and teach. Founded in 2018, we specialize in developing innovative digital learning platforms that make education accessible, engaging, and effective for students and educators worldwide. Our mission is to democratize quality education through cutting-edge technology and personalized learning experiences. We serve over 100,000 active users across Vietnam, Singapore, and Malaysia, partnering with educational institutions, corporations, and individual learners to deliver exceptional learning outcomes.',
    'Our company culture emphasizes innovation, collaboration, and continuous learning. We foster an open and inclusive environment where every team member is encouraged to share ideas and take ownership of projects. We believe in work-life balance and support flexible working arrangements, including remote work options. Regular team-building activities, hackathons, and learning sessions are integral to our culture. We celebrate diversity and value different perspectives that drive our creativity and problem-solving capabilities.',
    'Innovation: We constantly explore new technologies and methodologies to improve education.\nExcellence: We strive for the highest quality in everything we do.\nIntegrity: We maintain honesty and transparency in all our relationships.\nEmpowerment: We empower learners and educators to achieve their full potential.\nImpact: We measure success by the positive impact we create in the education sector.',
    'Our Platforms:\n• LearnHub: Comprehensive Learning Management System (LMS) for schools and universities, featuring interactive course creation, student progress tracking, and automated assessments.\n• SkillMaster: Online courses and skill development platform offering professional certifications in business, technology, and creative fields.\n• ExamPrep: AI-powered exam preparation and practice platform with adaptive learning algorithms and real-time feedback.\n• TeacherTools: Classroom management and assessment tools that help educators create engaging lessons and monitor student performance effectively.',
    'Frontend: React.js, Vue.js, TypeScript, HTML5, CSS3\nBackend: Node.js, Python (Django, Flask), PHP (Laravel)\nDatabase: PostgreSQL, MongoDB, Redis\nCloud & DevOps: AWS, Docker, Kubernetes, CI/CD pipelines\nAI/ML: TensorFlow, PyTorch, Natural Language Processing\nMobile: React Native, Flutter\nOther: RESTful APIs, GraphQL, Microservices architecture',
    'Competitive salary package with 13th-month bonus\nComprehensive health insurance for employees and dependents\nFlexible working hours and remote work options\nProfessional development budget (up to $1,000/year for courses and conferences)\nAnnual team retreat and company-wide events\nStock options for senior employees\nPaid time off: 15 days annual leave + public holidays\nFree access to all our learning platforms\nModern office with recreational facilities (game room, gym)\nFree lunch and snacks daily\nTransportation allowance\nLaptop and equipment provided',
    'Vietnam EdTech Startup of the Year 2020\nBest Educational Technology Platform - ASEAN Innovation Awards 2021\nTop 10 Fastest Growing EdTech Companies in Southeast Asia 2022\nISO 27001:2013 certified for information security\nGoogle for Education Partner',
    'Website: https://www.edtechsolutions.vn\nLinkedIn: https://www.linkedin.com/company/edtechsolutions\nFacebook: https://www.facebook.com/edtechsolutions\nTwitter: @EdTechSolutionsVN\nYouTube: EdTech Solutions Channel'
);

-- Company 2: TechNova Co., Ltd. - IT Services & Software Development
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    2, -- Update with actual employer_id from your database
    'TechNova Co., Ltd.',
    'Information Technology',
    2015,
    'Da Nang, Vietnam',
    '100-200 employees',
    'Technology',
    'TechNova Co., Ltd. is a premier software development and IT consulting company based in Da Nang, Vietnam. Since our establishment in 2015, we have grown into a trusted technology partner for businesses across various industries, including finance, healthcare, e-commerce, and logistics. We specialize in custom software development, cloud solutions, digital transformation, and enterprise application integration. Our team of experienced developers, designers, and consultants work collaboratively to deliver scalable, secure, and innovative solutions that drive business growth and operational efficiency.',
    'TechNova fosters a culture of technical excellence and continuous improvement. We encourage our team to stay updated with the latest industry trends and technologies through regular training programs and conference attendance. Our flat organizational structure promotes open communication and quick decision-making. We value autonomy and trust our employees to manage their work effectively. Team members are encouraged to contribute to open-source projects and share knowledge through internal tech talks and workshops. We maintain a healthy work environment that balances challenging projects with personal growth opportunities.',
    'Technical Excellence: We maintain the highest standards in code quality and software architecture.\nClient Focus: We prioritize understanding and exceeding client expectations.\nInnovation: We embrace new technologies and methodologies to solve complex problems.\nTeamwork: We believe in the power of collaboration and mutual support.\nContinuous Learning: We invest in our team\'s professional development and growth.',
    'Our Services:\n• Custom Software Development: Full-stack web applications, mobile apps (iOS/Android), and desktop solutions tailored to specific business needs.\n• Cloud Solutions: Migration to AWS, Azure, or Google Cloud, cloud architecture design, and DevOps implementation.\n• Digital Transformation: Legacy system modernization, process automation, and digital strategy consulting.\n• Enterprise Integration: API development, microservices architecture, and system integration services.\n• Quality Assurance: Comprehensive testing services including automated testing, performance testing, and security auditing.\n• IT Consulting: Technical advisory, architecture reviews, and technology stack recommendations.',
    'Programming Languages: Java, C#, Python, JavaScript, TypeScript, Go, Ruby\nFrameworks: Spring Boot, .NET Core, Django, Express.js, Angular, React, Vue.js\nDatabases: MySQL, PostgreSQL, Oracle, MongoDB, Cassandra\nCloud Platforms: AWS (EC2, S3, Lambda, RDS), Azure, Google Cloud Platform\nDevOps Tools: Jenkins, GitLab CI, Docker, Kubernetes, Terraform\nMobile: React Native, Flutter, Native iOS/Android development\nTesting: JUnit, Jest, Selenium, Cypress, LoadRunner\nOther: Microservices, RESTful APIs, GraphQL, Message Queues (RabbitMQ, Kafka)',
    'Attractive salary with performance bonuses (up to 3 months salary/year)\nPremium health insurance package covering family members\nFlexible work schedule with core hours (10 AM - 4 PM)\n100% remote work option available\nTraining and certification support (company-sponsored courses)\nAnnual company trip and quarterly team-building activities\nFree English classes and soft skills training\nBirthday gifts and annual health check-up\nModern development setup (MacBook Pro or equivalent)\nStock options program for outstanding contributors\nAnnual salary review and promotion opportunities\nPaid maternity/paternity leave (beyond legal requirements)',
    'Microsoft Gold Partner for Application Development\nAWS Advanced Consulting Partner\nTop IT Services Company in Central Vietnam 2021-2023\nCMMI Level 3 certified\nGreat Place to Work certification 2022',
    'Website: https://www.technova.vn\nLinkedIn: https://www.linkedin.com/company/technova\nFacebook: https://www.facebook.com/technova\nGitHub: github.com/technova'
);

-- Company 3: FinanceWorks Asia - Financial Technology & Services
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    3, -- Update with actual employer_id from your database
    'FinanceWorks Asia',
    'Financial Services',
    2016,
    'Hanoi, Vietnam',
    '200-500 employees',
    'Finance',
    'FinanceWorks Asia is a leading fintech company providing innovative financial solutions and services across Southeast Asia. Established in 2016, we combine cutting-edge technology with deep financial expertise to deliver products that make banking and financial management more accessible, efficient, and secure. Our comprehensive suite of solutions serves both individual consumers and businesses, including digital banking platforms, payment processing systems, lending solutions, and investment management tools. We are licensed and regulated, maintaining the highest standards of security and compliance in all our operations.',
    'FinanceWorks Asia operates in a fast-paced, results-oriented environment while maintaining a supportive and inclusive workplace culture. We value diversity and believe that varied perspectives drive innovation in financial services. Our culture emphasizes accountability, transparency, and ethical conduct in all business activities. We provide clear career paths and opportunities for advancement, with many team members growing from junior positions to leadership roles. Regular town halls, feedback sessions, and open-door policies ensure that every voice is heard. We also organize financial literacy workshops and investment seminars as part of our commitment to employee education.',
    'Trust: We build and maintain trust through transparency and reliability.\nSecurity: We prioritize the security and privacy of customer data above all.\nInnovation: We continuously innovate to stay ahead in the rapidly evolving fintech landscape.\nCompliance: We adhere strictly to regulatory requirements and industry best practices.\nCustomer Success: We measure our success by the value we deliver to our customers.',
    'Our Solutions:\n• Digital Banking Platform: Comprehensive mobile and web banking solutions with features including account management, transfers, bill payments, and investment tracking.\n• Payment Gateway: Secure payment processing system for e-commerce merchants with support for multiple payment methods (credit cards, e-wallets, bank transfers).\n• Lending Solutions: Personal loans, business loans, and micro-financing products with automated credit assessment and risk management.\n• Investment Platform: Digital wealth management and robo-advisory services helping customers build investment portfolios.\n• Financial Analytics: Business intelligence tools for financial institutions to analyze customer behavior and optimize operations.\n• Regulatory Technology: Compliance and reporting solutions to help financial institutions meet regulatory requirements efficiently.',
    'Backend: Java (Spring Framework), Python (FastAPI), Node.js\nFrontend: React.js, Angular, TypeScript, React Native\nDatabases: Oracle, PostgreSQL, MySQL, Redis (caching)\nSecurity: OAuth 2.0, JWT, SSL/TLS, encryption at rest and in transit\nCloud Infrastructure: AWS (private cloud setup), on-premise hybrid solutions\nIntegration: RESTful APIs, SOAP services, message brokers (IBM MQ, RabbitMQ)\nData Analytics: Apache Spark, Hadoop, Elasticsearch, Tableau\nBlockchain: Hyperledger Fabric (for certain use cases)\nCompliance Tools: Automated reporting systems, audit trail mechanisms',
    'Competitive salary with performance-based bonuses (quarterly and annual)\nComprehensive health and life insurance\nAnnual health check-up and wellness programs\nProfessional development opportunities (CFA, FRM, tech certifications sponsored)\nFlexible working arrangements (hybrid model: 3 days office, 2 days remote)\nStock options and profit-sharing plans\nGenerous annual leave (20 days + public holidays)\nLoan interest subsidy program for employees\nTransportation and meal allowances\nModern office in prime location with premium facilities\nEmployee assistance program (counseling and support services)\nFree financial advisory services for employees',
    'Best Fintech Company in Vietnam - Vietnam Fintech Awards 2021, 2022\nPCI DSS Level 1 certified for payment card data security\nISO/IEC 27001:2013 certified for information security management\nLicensed by State Bank of Vietnam for payment intermediary services\nInnovation Award - Vietnam Digital Transformation Awards 2023',
    'Website: https://www.financeworks.asia\nLinkedIn: https://www.linkedin.com/company/financeworks-asia\nFacebook: https://www.facebook.com/financeworksasia\nTwitter: @FinanceWorksAsia'
);

-- ============================================================================
-- Step 3: Update EMPLOYERS table descriptions with rich content
-- ============================================================================

-- Update EdTech Solutions employer description
UPDATE `EMPLOYERS` 
SET `description` = 'EdTech Solutions is a leading educational technology company in Southeast Asia, dedicated to transforming how people learn and teach. Founded in 2018, we specialize in developing innovative digital learning platforms including LearnHub (LMS), SkillMaster (online courses), ExamPrep (AI-powered exam preparation), and TeacherTools (classroom management). We serve over 100,000 active users across Vietnam, Singapore, and Malaysia. Our mission is to democratize quality education through cutting-edge technology and personalized learning experiences. We offer competitive benefits including health insurance, flexible work arrangements, professional development budget, and modern office facilities. Join us to make a meaningful impact in education!'
WHERE `company_name` = 'EdTech Solutions' OR `id` = 1;

-- Update TechNova employer description
UPDATE `EMPLOYERS` 
SET `description` = 'TechNova Co., Ltd. is a premier software development and IT consulting company based in Da Nang, Vietnam. Since 2015, we have delivered custom software solutions, cloud migrations, and digital transformation projects for clients across finance, healthcare, e-commerce, and logistics industries. We specialize in full-stack development, cloud architecture, and enterprise integration. Our team of 100-200 experienced professionals works with modern technologies including Java, Python, React, AWS, and Kubernetes. We offer competitive compensation, remote work options, training support, and a culture that values technical excellence and continuous learning. Microsoft Gold Partner and AWS Advanced Consulting Partner.'
WHERE `company_name` = 'TechNova Co., Ltd.' OR `id` = 2;

-- Update FinanceWorks Asia employer description
UPDATE `EMPLOYERS` 
SET `description` = 'FinanceWorks Asia is a leading fintech company providing innovative financial solutions across Southeast Asia. Established in 2016, we deliver digital banking platforms, payment gateways, lending solutions, and investment management tools. Licensed and regulated, we serve both individual consumers and businesses with secure, compliant, and user-friendly financial products. Our team of 200-500 professionals combines deep financial expertise with cutting-edge technology. We offer attractive compensation, health insurance, stock options, and professional development opportunities. Winner of Best Fintech Company in Vietnam (2021, 2022) and ISO 27001 certified for information security.'
WHERE `company_name` = 'FinanceWorks Asia' OR `id` = 3;

-- ============================================================================
-- Notes:
-- 1. Update employer_id values (1, 2, 3) with actual IDs from your EMPLOYERS table
-- 2. Run this script in phpMyAdmin or MySQL command line
-- 3. These profiles provide rich data for AI to answer questions about companies
-- 4. You can add more companies following the same format
-- ============================================================================

-- ============================================================================
-- Extra Sample Companies for COMPANY_PROFILES (4–13)
-- ============================================================================

-- Company 4: GreenLeaf Commerce - Sustainable E-commerce Platform
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    4,
    'GreenLeaf Commerce',
    'E-commerce',
    2019,
    'Ho Chi Minh City, Vietnam',
    '50-100 employees',
    'E-commerce & Retail',
    'GreenLeaf Commerce is a sustainable e-commerce platform focused on eco-friendly and locally sourced products. We connect conscious consumers with verified green brands across Vietnam and Southeast Asia. By leveraging technology, we make it easy for users to discover environmentally responsible products, track their carbon footprint, and support local producers. Our marketplace features categories such as home & living, organic food, fashion, and sustainable lifestyle accessories.',
    'We promote a culture of sustainability, transparency, and ownership. Team members are encouraged to propose green initiatives, volunteer in community activities, and continuously improve our environmental impact. Decisions are made based on data, customer feedback, and long-term value rather than short-term gains. We maintain an informal, friendly workplace where everyone can share ideas freely and work cross-functionally.',
    'Sustainability: We prioritize eco-friendly practices and products.\nCustomer Trust: We provide transparent information and verified suppliers.\nCommunity: We support local producers and small businesses.\nInnovation: We experiment with new tools to reduce waste and emissions.\nIntegrity: We operate with honesty and accountability.',
    'Our Solutions:\n• Green Marketplace: Online platform featuring curated eco-friendly products.\n• Vendor Portal: Tools for sellers to manage inventory, orders, and marketing campaigns.\n• Impact Dashboard: Metrics showing CO2 reduction and sustainable choices for each order.\n• Subscription Boxes: Themed eco-boxes (zero waste, organic food, home care) delivered monthly.\n• Corporate Gifting: Sustainable gift sets for companies and events.',
    'Frontend: React.js, Next.js, TypeScript\nBackend: Node.js (Express), PHP (Laravel)\nDatabase: MySQL, Redis (caching)\nInfra: AWS (EC2, S3, CloudFront), Docker\nPayments: Stripe, local e-wallet integrations\nAnalytics: Google Analytics, Mixpanel\nOther: RESTful APIs, Microservices, Message Queues (RabbitMQ)',
    'Competitive salary with performance bonus\n13th-month salary and annual review\nHybrid working model (3 days office, 2 days remote)\nHealth insurance and annual health check-up\nMonthly eco-perks budget for buying green products\nTeam-building trips and CSR activities\nLearning budget for courses on sustainability and technology\nModern office with bike parking and wellness corner',
    'Top 50 Innovative E-commerce Startups in Asia 2022\nVietnam Green Business Award 2023\nFeatured in Vietnam Economic Times and Tech in Asia',
    'Website: https://www.greenleafcommerce.vn\nLinkedIn: https://www.linkedin.com/company/greenleaf-commerce\nFacebook: https://www.facebook.com/greenleafcommerce'
);

-- Company 5: MediCare Digital Health - Healthcare Technology
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    5,
    'MediCare Digital Health',
    'Healthcare Technology',
    2017,
    'Hanoi, Vietnam',
    '100-200 employees',
    'Healthcare & Technology',
    'MediCare Digital Health is a health-tech company dedicated to improving patient care through digital solutions. We work with hospitals, clinics, and pharmacies to digitize workflows, enhance patient engagement, and enable data-driven decision-making. Our mission is to make quality healthcare more accessible, efficient, and personalized across Vietnam and the broader ASEAN region.',
    'We nurture a mission-driven culture where patient outcomes come first. Our multidisciplinary teams of engineers, doctors, product managers, and data scientists work closely together to design solutions that truly solve real-world problems. We emphasize empathy, ethical use of data, and regulatory compliance. Continuous learning, cross-team collaboration, and knowledge-sharing are core parts of our daily work.',
    'Patient-Centric: We design with patients and clinicians in mind.\nSafety & Compliance: We strictly follow healthcare regulations and data privacy laws.\nCollaboration: We work closely with medical professionals and partners.\nInnovation: We explore AI and data analytics to improve care.\nAccountability: We measure impact using concrete health and operational metrics.',
    'Our Products:\n• MediCare EMR: Electronic Medical Records platform for hospitals and clinics.\n• TeleCare: Telemedicine and online consultation platform for patients.\n• Pharmacy Connect: Integrated system for prescriptions, inventory, and billing.\n• CareInsight: Analytics platform for hospital operations and clinical outcomes.\n• Patient App: Mobile app for booking appointments, viewing medical history, and receiving reminders.',
    'Backend: Java (Spring Boot), Python (Django)\nFrontend: React.js, Vue.js\nMobile: React Native\nDatabase: PostgreSQL, MongoDB\nCloud: AWS, Azure\nSecurity: OAuth2, JWT, HL7/FHIR standards\nData: Apache Kafka, Elasticsearch, BI tools',
    'Competitive salary and quarterly performance bonus\nComprehensive health and accident insurance\nAnnual health screening and wellness programs\nHybrid work policy (remote and on-site at partner hospitals)\nTraining support for healthcare and technology certifications\nSponsored attendance at local and international conferences\nLunch allowance and transportation support\nGenerous annual leave and sick leave policy',
    'Top 10 HealthTech Startups in Vietnam 2021\nDigital Transformation Award in Healthcare 2022\nISO 27001 certified for information security',
    'Website: https://www.medicaredigital.vn\nLinkedIn: https://www.linkedin.com/company/medicare-digital-health\nFacebook: https://www.facebook.com/medicaredigitalhealth'
);

-- Company 6: SkyGrid Games Studio - Game Development
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    6,
    'SkyGrid Games Studio',
    'Gaming',
    2014,
    'Ho Chi Minh City, Vietnam',
    '50-150 employees',
    'Game Development',
    'SkyGrid Games Studio is a creative game development studio focused on mobile and PC games for global audiences. We specialize in mid-core and casual titles with engaging gameplay, strong storytelling, and high production values. Our games have been featured on major app stores and played by millions of users worldwide.',
    'We foster a fun, inclusive, and highly creative environment. Designers, artists, and engineers work closely together in cross-functional squads. We encourage experimentation, rapid prototyping, and open feedback. Team members are given autonomy to explore new game concepts and user experiences. We also support a healthy work-life balance and avoid crunch culture.',
    'Creativity: We push boundaries in gameplay and visuals.\nPlayer Focus: We actively listen to player feedback and community insights.\nQuality: We invest in polish, performance, and user experience.\nTeam Spirit: We succeed together and learn from failures.\nGrowth: We help our people grow their craft and careers.',
    'Our Portfolio:\n• SkyLegends: Online multiplayer RPG with guild battles and seasonal events.\n• PuzzleVerse: Cross-platform puzzle game with daily challenges.\n• CitySkies: Simulation and city-building game with social features.\nServices:\n• Co-development with international publishers\n• LiveOps and analytics-driven game optimization',
    'Game Engines: Unity, Unreal Engine\nProgramming: C#, C++, Java\nArt: Blender, Maya, Photoshop, Spine\nBackend: Node.js, Go, Kubernetes\nAnalytics: Firebase, GameAnalytics, custom dashboards\nPlatforms: iOS, Android, Steam\nOther: In-app purchases, Ads mediation, A/B testing tools',
    'Competitive salary and revenue-sharing model for key projects\nFlexible working hours and remote days\nHealth insurance and gaming allowance\nConference and game jam sponsorships\nIn-house workshops and mentorships\nCasual dress code, free snacks, and game room\nAnnual company trip and regular team parties',
    'Featured in App Store and Google Play multiple times\nWinner of Best Mobile Game at Vietnam Game Awards 2020\nPartnered with regional and global game publishers',
    'Website: https://www.skygridgames.com\nLinkedIn: https://www.linkedin.com/company/skygrid-games-studio\nFacebook: https://www.facebook.com/skygridgames\nYouTube: SkyGrid Games Channel'
);

-- Company 7: SmartLogiTech - Smart Logistics & Supply Chain
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    7,
    'SmartLogiTech',
    'Logistics & Supply Chain',
    2013,
    'Ho Chi Minh City, Vietnam',
    '200-400 employees',
    'Logistics Technology',
    'SmartLogiTech is a logistics technology company providing end-to-end supply chain visibility and optimization solutions for manufacturers, distributors, and e-commerce businesses. We combine IoT, data analytics, and automation to help clients reduce costs, improve delivery times, and increase customer satisfaction.',
    'Our culture is built on reliability, innovation, and customer success. We value clear communication, disciplined execution, and practical problem-solving. Cross-functional teams consisting of logistics experts, software engineers, and data analysts work closely with customers to design and deploy solutions. We embrace continuous improvement and lean practices in both our operations and products.',
    'Reliability: We ensure systems and operations run smoothly at scale.\nCustomer Success: We measure our performance by our clients'' KPIs.\nInnovation: We experiment with new technologies to optimize logistics.\nIntegrity: We are transparent and accountable in all engagements.\nSustainability: We help clients reduce emissions and waste.',
    'Our Solutions:\n• SmartFleet: Fleet management, route optimization, and real-time tracking.\n• WarehousePro: Warehouse management system with barcode and RFID support.\n• ChainView: Supply chain visibility dashboard and analytics.\n• LastMile: Last-mile delivery optimization for e-commerce.\n• Integration Services: APIs and connectors to ERP, TMS, and e-commerce platforms.',
    'Backend: Java (Spring), .NET Core\nFrontend: Angular, React.js\nDatabases: PostgreSQL, SQL Server, Redis\nInfra: AWS, on-premise deployments\nIoT: GPS tracking devices, RFID, MQTT\nData: Apache Kafka, Spark, Power BI dashboards',
    'Competitive salary and KPI-based bonus\nCompany car/transportation allowance for certain roles\nHealth insurance and annual health check\nProfessional training in logistics and supply chain management\nOpportunities to work with international clients\nHybrid work model for non-operations roles\nAnnual company trip and quarterly team-building events',
    'Top Logistics Tech Solution Provider in Vietnam 2021\nRecognized by Vietnam Logistics Business Association\nKey technology partner for multiple leading 3PLs',
    'Website: https://www.smartlogitech.vn\nLinkedIn: https://www.linkedin.com/company/smartlogitech\nFacebook: https://www.facebook.com/smartlogitech'
);

-- Company 8: Aurora Creative Labs - Digital Marketing & Branding
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    8,
    'Aurora Creative Labs',
    'Marketing & Advertising',
    2012,
    'Ho Chi Minh City, Vietnam',
    '50-120 employees',
    'Creative Agency',
    'Aurora Creative Labs is a full-service digital marketing and branding agency helping brands build meaningful connections with their audiences. We work with startups, SMEs, and multinational corporations across various industries to craft compelling stories, engaging digital experiences, and measurable marketing campaigns.',
    'We operate as a collaborative studio where strategists, designers, copywriters, and technologists work side by side. Our culture values curiosity, experimentation, and craftsmanship. We encourage open feedback, continuous learning, and a growth mindset. We also support flexible working styles and respect personal creativity and diversity.',
    'Creativity: We aim for original, impactful ideas.\nStrategy: We make decisions backed by data and insights.\nCraft: We care about details in execution.\nPartnership: We treat clients as long-term partners.\nTransparency: We communicate openly about goals, results, and budgets.',
    'Our Services:\n• Brand Strategy and Identity Design\n• Digital Campaigns (social media, search, display)\n• Content Production (video, photo, copywriting)\n• Website and Landing Page Design\n• Performance Marketing and Analytics\n• Employer Branding and Internal Communications',
    'Design: Figma, Adobe Creative Cloud (Photoshop, Illustrator, After Effects)\nWeb: HTML5, CSS3, JavaScript, WordPress, Webflow\nAnalytics: Google Analytics, Google Data Studio, Facebook Business Manager\nMarketing Tools: HubSpot, Mailchimp, Hootsuite\nProject Management: Asana, Notion, Slack',
    'Competitive salary and project-based bonuses\nFlexible working hours and hybrid work option\nCreative allowance and training budget\nMacBook or equivalent equipment provided\nRegular workshops, portfolio reviews, and mentoring\nCasual, pet-friendly office\nAnnual company trip and creative retreat',
    'Vietnam Digital Agency of the Year (Silver) 2020\nMultiple campaign awards from local marketing associations\nFeatured in regional design and creativity publications',
    'Website: https://www.auroracreativelabs.com\nLinkedIn: https://www.linkedin.com/company/aurora-creative-labs\nFacebook: https://www.facebook.com/auroracreativelabs\nInstagram: @aurora.creative.labs'
);

-- Company 9: EcoVolt Energy Systems - Renewable Energy
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    9,
    'EcoVolt Energy Systems',
    'Renewable Energy',
    2016,
    'Da Nang, Vietnam',
    '80-150 employees',
    'Energy & Engineering',
    'EcoVolt Energy Systems designs, deploys, and operates renewable energy solutions, including rooftop solar, energy storage, and energy efficiency projects. We serve industrial, commercial, and residential customers, helping them reduce electricity costs and carbon emissions. Our mission is to accelerate the transition towards a cleaner, more sustainable energy future in Vietnam and the region.',
    'We maintain a safety-first, engineering-driven culture with a strong sense of environmental responsibility. Field engineers, project managers, and analysts work closely to deliver high-quality, reliable systems. We invest in training, certifications, and on-site safety standards. Open communication, teamwork, and integrity are highly valued.',
    'Safety: We comply with rigorous safety standards in all projects.\nSustainability: We focus on long-term environmental benefits.\nQuality: We use high-quality equipment and best practices.\nCustomer Focus: We tailor solutions to each customer’s needs.\nInnovation: We explore new technologies such as battery storage and smart grids.',
    'Our Solutions:\n• Rooftop Solar for industrial and commercial clients\n• Solar-as-a-Service with zero upfront cost options\n• Energy Storage and backup power systems\n• Energy Audits and Energy Efficiency consulting\n• Monitoring platform for real-time energy tracking',
    'Engineering: PV design tools, CAD\nHardware: Tier-1 solar modules, inverters, battery systems\nSoftware: Custom monitoring dashboards, IoT gateways\nCloud: AWS, Azure for data storage and analytics\nProject Tools: MS Project, Primavera, ERP integrations',
    'Competitive salary and performance bonus\nField allowance for project-based roles\nHealth and accident insurance\nSafety and technical training programs\nOpportunities to gain international certifications\nAnnual trip and team-building\nFlexible working arrangements for office-based roles',
    'Top Renewable Energy Solution Provider in Vietnam 2022\nRecognized by local industry associations\nPartner of multiple global solar and inverter brands',
    'Website: https://www.ecovoltenergy.vn\nLinkedIn: https://www.linkedin.com/company/ecovolt-energy-systems\nFacebook: https://www.facebook.com/ecovoltenergy'
);

-- Company 10: NovaShield Cybersecurity - Security Services
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    10,
    'NovaShield Cybersecurity',
    'Cybersecurity',
    2018,
    'Ho Chi Minh City, Vietnam',
    '40-80 employees',
    'Security Services',
    'NovaShield Cybersecurity provides end-to-end cybersecurity services for SMEs and enterprises, including vulnerability assessments, managed security operations, incident response, and security awareness training. Our experts help organizations protect sensitive data, comply with regulations, and respond quickly to emerging threats.',
    'We foster a culture of vigilance, continuous learning, and ethical responsibility. Our team consists of security analysts, penetration testers, and security architects who enjoy solving complex challenges. We support certification paths (CEH, CISSP, OSCP) and encourage participation in CTFs and security communities.',
    'Integrity: We act responsibly with access to sensitive data.\nExcellence: We maintain high technical standards.\nConfidentiality: We protect client information at all times.\nEducation: We help clients build security awareness.\nAgility: We respond quickly to new threats.',
    'Our Services:\n• Vulnerability Assessment and Penetration Testing\n• Managed Security Operations Center (SOC)\n• Incident Response and Forensics\n• Security Architecture Design and Review\n• Compliance Support (ISO 27001, PCI DSS)\n• Security Awareness Training',
    'Security Tools: SIEM, IDS/IPS, endpoint protection suites\nTechnologies: Linux, Windows Server, Cloud security tools\nLanguages: Python, Bash, PowerShell\nPlatforms: AWS, Azure, on-premise\nFrameworks: MITRE ATT&CK, NIST, ISO 27001',
    'Competitive salary with project bonus\nCertification sponsorship (exam fees and training)\nFlexible working time and remote work options\nHealth insurance and wellness programs\nConference sponsorship (local and international security events)\nModern security lab environment\nLearning days dedicated to research and internal projects',
    'Recognized as a Rising Star in Cybersecurity Services 2022\nTrusted security partner for multiple fintech and e-commerce companies',
    'Website: https://www.novashieldsecurity.com\nLinkedIn: https://www.linkedin.com/company/novashield-cybersecurity\nFacebook: https://www.facebook.com/novashieldsecurity'
);

-- Company 11: UrbanNest Real Estate Tech - PropTech
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    11,
    'UrbanNest Real Estate Tech',
    'Property Technology',
    2019,
    'Ho Chi Minh City, Vietnam',
    '30-70 employees',
    'PropTech Startup',
    'UrbanNest Real Estate Tech builds digital products that simplify how people search, evaluate, and transact real estate. Our platform connects buyers, renters, and landlords with verified listings, virtual tours, and data-driven insights about neighborhoods and property values.',
    'We embrace a product-driven culture with a strong focus on user experience and experimentation. Product managers, engineers, designers, and data analysts collaborate in agile squads. We value transparency, honest feedback, and user research. Remote-friendly work and flexible hours support individual productivity styles.',
    'User Obsession: We deeply understand user needs and pain points.\nTransparency: We promote clear and honest property information.\nData-Driven: We rely on data to guide decisions.\nCollaboration: We work together across disciplines.\nOwnership: Each team owns end-to-end outcomes.',
    'Our Products:\n• UrbanNest Listings Platform: Verified property listings with rich media.\n• Virtual Tour & 3D Walkthroughs\n• Smart Recommendations for buyers and renters\n• Market Insights Dashboard for agents and developers\n• Rental Management tools for landlords',
    'Frontend: React.js, Next.js, Tailwind CSS\nBackend: Node.js, NestJS\nDatabase: PostgreSQL, Elasticsearch\nInfra: AWS, Docker, Kubernetes\nData: Python, Airflow, BI tools\nIntegrations: Payment gateways, mapping APIs (Google Maps)',
    'Competitive salary and stock option plan\nFlexible working hours and remote-first policy\nHealth insurance and wellness stipend\nLearning budget for courses and conferences\nModern equipment and coworking-space style office\nRegular team-building and product hack days',
    'Top 20 PropTech Startups in Southeast Asia 2023\nRecognized by local real estate associations',
    'Website: https://www.urbannest.vn\nLinkedIn: https://www.linkedin.com/company/urbannest-proptech\nFacebook: https://www.facebook.com/urbannest.vn'
);

-- Company 12: BlueWave Hospitality Group - Hotels & Lifestyle
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    12,
    'BlueWave Hospitality Group',
    'Hospitality',
    2010,
    'Nha Trang, Vietnam',
    '300-800 employees',
    'Hospitality & Tourism',
    'BlueWave Hospitality Group operates a portfolio of beachfront hotels, resorts, and lifestyle properties across Vietnam. We focus on delivering memorable guest experiences, blending local culture with modern comfort and sustainable practices.',
    'Our culture emphasizes service excellence, teamwork, and respect. We provide continuous training for staff, clear career progression paths, and recognition programs for outstanding performance. Diversity, local community engagement, and environmental stewardship are integral to how we operate.',
    'Service Excellence: We go the extra mile for guests.\nRespect: We value our people, guests, and local communities.\nSustainability: We adopt eco-friendly practices in our operations.\nIntegrity: We uphold high ethical and professional standards.\nGrowth: We invest in our employees’ development.',
    'Our Offerings:\n• Beachfront resorts and city hotels\n• Event and conference services\n• F&B outlets with local and international cuisine\n• Spa and wellness services\n• Tour and activity packages',
    'Property Management Systems (PMS)\nBooking and Channel Management tools\nCRM and loyalty platforms\nStandard office tools for operations\nOnline travel agency (OTA) integrations',
    'Competitive salary and service charge\nAccommodation and meals for staff in certain locations\nHealth insurance and annual health check\nTraining and development programs\nStaff discounts on stays and F&B\nMonthly recognition awards\nAnnual company events and outings',
    'Multiple hospitality and service awards from local tourism boards\nHighly rated across major travel platforms',
    'Website: https://www.bluewavehospitality.vn\nLinkedIn: https://www.linkedin.com/company/bluewave-hospitality\nFacebook: https://www.facebook.com/bluewavehospitality'
);

-- Company 13: DataForge Analytics - Data & AI Consulting
INSERT INTO `COMPANY_PROFILES` (
    `employer_id`,
    `company_name`,
    `industry`,
    `founded_year`,
    `headquarters`,
    `employee_count`,
    `company_type`,
    `about_us`,
    `company_culture`,
    `core_values`,
    `products_services`,
    `technologies`,
    `benefits`,
    `awards_recognition`,
    `social_links`
) VALUES (
    13,
    'DataForge Analytics',
    'Data & AI',
    2018,
    'Ho Chi Minh City, Vietnam',
    '40-100 employees',
    'Data & AI Consulting',
    'DataForge Analytics is a boutique data and AI consulting firm helping organizations unlock value from their data. We design data platforms, build analytics solutions, and develop machine learning models tailored to each client’s business needs.',
    'We maintain a learning-first culture where consultants and engineers are encouraged to explore new tools, share knowledge, and experiment. Project teams are small, cross-functional, and empowered to make decisions. We prioritize clear communication, documentation, and long-term partnerships with clients.',
    'Impact: We focus on solutions that deliver measurable business value.\nClarity: We communicate complex ideas in simple terms.\nCraft: We care about code quality and data reliability.\nCuriosity: We constantly learn and explore new techniques.\nEthics: We use data responsibly and respect privacy.',
    'Our Services:\n• Data Strategy and Roadmapping\n• Data Warehouse and Lakehouse implementations\n• Business Intelligence dashboards and self-service analytics\n• Machine Learning model development and deployment\n• MLOps and data platform modernization',
    'Cloud: AWS, Azure, Google Cloud\nData: Snowflake, BigQuery, Redshift, Databricks\nETL/ELT: dbt, Airflow, Fivetran\nBI: Power BI, Tableau, Looker\nML: Python, scikit-learn, TensorFlow, PyTorch\nDevOps: Docker, Kubernetes, CI/CD pipelines',
    'Competitive salary with project-based bonus\nRemote-friendly working culture\nHealth insurance and learning stipend\nBudget for certifications, courses, and conferences\nTop-tier equipment and tooling\nRegular knowledge-sharing sessions and internal tech talks',
    'Recognized as a promising data & AI partner in Vietnam\nTrusted by clients in retail, finance, and manufacturing',
    'Website: https://www.dataforgeanalytics.vn\nLinkedIn: https://www.linkedin.com/company/dataforge-analytics\nFacebook: https://www.facebook.com/dataforgeanalytics'
);
