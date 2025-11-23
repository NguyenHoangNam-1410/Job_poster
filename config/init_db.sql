USE job_poster;

-- Users
INSERT INTO `users` (`UID`, `Email`, `Password`, `Role`, `Name`, `Avatar`, `auth_provider`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'alice@example.com', 'hashed_pw1', 'Staff', 'Alice Nguyen', '/Worknest/public/image/avatar/1/avatar_1763266714.png', 'local', 1, '2025-11-05 13:08:06', '2025-11-16 04:18:34'),
(2, 'bob@example.com', 'hashed_pw2', 'Admin', 'Bob Tran', '/Worknest/public/image/avatar/2/avatar_1763267198.png', 'local', 1, '2025-11-05 13:08:06', '2025-11-16 04:26:38'),
(3, 'chris@example.com', 'hashed_pw3', 'Employer', 'Chris Le', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(4, 'diana@example.com', 'hashed_pw4', 'Employer', 'Diana Pham', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(5, 'edward@example.com', 'hashed_pw5', 'Employer', 'Edward Vu', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(6, 'fiona@example.com', 'hashed_pw6', 'Staff', 'Fiona Huynh', '/Worknest/public/image/avatar/6/avatar_1763265481.jpg', 'local', 1, '2025-11-05 13:08:06', '2025-11-16 03:58:01'),
(7, 'george@example.com', 'hashed_pw7', 'Admin', 'George Dao', '/Worknest/public/image/avatar/7/avatar_1763268812.png', 'local', 1, '2025-11-05 13:08:06', '2025-11-16 04:53:32'),
(8, 'hannah@example.com', 'hashed_pw8', 'Employer', 'Hannah Trinh', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(9, 'ivan@example.com', 'hashed_pw9', 'Employer', 'Ivan Bui', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(10, 'jasmine@example.com', 'hashed_pw10', 'Employer', 'Jasmine Ngo', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(11, 'kevin@example.com', 'hashed_pw11', 'Employer', 'Kevin Truong', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(12, 'linda@example.com', 'hashed_pw12', 'Employer', 'Linda Ho', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(13, 'michael@example.com', 'hashed_pw13', 'Employer', 'Michael Dang', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(14, 'natalie@example.com', 'hashed_pw14', 'Employer', 'Natalie Phan', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(15, 'oliver@example.com', 'hashed_pw15', 'Employer', 'Oliver Vo', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(16, 'phoebe@example.com', 'hashed_pw16', 'Employer', 'Phoebe Dinh', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(17, 'quentin@example.com', 'hashed_pw17', 'Employer', 'Quentin Lam', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(18, 'rachel@example.com', 'hashed_pw18', 'Employer', 'Rachel Le', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(19, 'sam@example.com', 'hashed_pw19', 'Employer', 'Sam Nguyen', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(20, 'tina@example.com', 'hashed_pw20', 'Employer', 'Tina Tran', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(21, 'unknown@example.com', 'hashed_pw21', 'Employer', 'Unknown User', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(22, 'nguyen@example.com', 'hashed_pw22', 'Employer', 'Nguyen Van A', NULL, 'local', 1, '2025-11-05 13:08:06', '2025-11-05 13:08:06'),
(23, 'test_1763129661@example.com', '$2y$10$WbttonZgG9WYXWqTp7LWaeI.uhxXpx1pU.cIraFrzqjEhiRXN8Flq', 'Employer', 'Test User', NULL, 'local', 1, '2025-11-14 14:14:21', '2025-11-14 14:14:21'),
(24, 'silversoul66666@gmail.com', '$2y$10$FJwn.z8GWZOqwx.7A0DGZ.niTr2PrFPjO1j/D3GcXW3I1JuSYpExS', 'Employer', 'Nguyen Hoang Nam', NULL, 'local', 1, '2025-11-14 14:14:57', '2025-11-14 14:14:57'),
(25, 'worknest@123', '$2y$10$.ztJ7lvF98dB9NkAQAqAhu2v0rYz7P0Ke7wVHe5roI1jCrlctwYNC', 'Admin', 'WorkNest', NULL, NULL, 1, '2025-11-22 02:32:16', '2025-11-22 02:32:16'),
(26, 'staff@123', '$2y$10$e6kMZ641a85blQjEAkDfMuueo9.Bsw2FreXXxTK4rgZ6LWSfq3ShO', 'Staff', 'Moderator', NULL, NULL, 1, '2025-11-22 02:32:33', '2025-11-22 02:32:33');

-- Employers
INSERT INTO `employers` (`id`, `user_id`, `company_name`, `website`, `logo`, `contact_phone`, `contact_email`, `contact_person`, `description`, `created_at`) VALUES
(1, 3, 'TechNova Co., Ltd', 'https://technova.vn', NULL, '0901234567', 'contact@technova.vn', 'Chris Le', 'Innovative software company based in Ho Chi Minh City.', '2025-11-23 02:20:29'),
(2, 4, 'Designify Studio', 'https://designify.vn', NULL, '0902345678', 'hello@designify.vn', 'Diana Pham', 'Creative design and branding agency in Da Nang.', '2025-11-23 02:20:29'),
(3, 5, 'DataSense Analytics', 'https://datasense.vn', NULL, '0903456789', 'info@datasense.vn', 'Edward Vu', 'Data analytics and business intelligence solutions provider in Hanoi.', '2025-11-23 02:20:29'),
(4, 8, 'EduLink Academy', 'https://edulink.vn', NULL, '0904567890', 'support@edulink.vn', 'Hannah Trinh', 'E-learning and training platform helping students learn tech skills.', '2025-11-23 02:20:29'),
(5, 9, 'InternHub Vietnam', 'https://internhub.vn', NULL, '0905678901', 'hr@internhub.vn', 'Ivan Bui', 'Connecting students with tech internships across Vietnam.', '2025-11-23 02:20:29'),
(6, 10, 'UXLabs Digital', 'https://uxlabs.vn', NULL, '0906789012', 'contact@uxlabs.vn', 'Jasmine Ngo', 'User experience and digital product design consultancy.', '2025-11-23 02:20:29'),
(7, 11, 'PeopleFirst HR', 'https://peoplefirst.vn', NULL, '0907890123', 'info@peoplefirst.vn', 'Kevin Truong', 'Human resources and staffing solutions provider.', '2025-11-23 02:20:29'),
(8, 12, 'FinanceWorks Asia', 'https://financeworks.vn', NULL, '0908901234', 'support@financeworks.vn', 'Linda Ho', 'Financial consulting and accounting technology services.', '2025-11-23 02:20:29'),
(9, 13, 'CloudOps Tech', 'https://cloudops.vn', NULL, '0909012345', 'info@cloudops.vn', 'Michael Dang', 'Cloud infrastructure and DevOps automation specialists.', '2025-11-23 02:20:29'),
(10, 14, 'SecureNet Solutions', 'https://securenet.vn', NULL, '0910123456', 'admin@securenet.vn', 'Natalie Phan', 'Cybersecurity and IT protection services for enterprises.', '2025-11-23 02:20:29'),
(11, 15, 'DocuTech Writing', 'https://docutech.vn', NULL, '0911234567', 'contact@docutech.vn', 'Oliver Vo', 'Technical writing and documentation outsourcing agency.', '2025-11-23 02:20:29'),
(12, 16, 'SEOBoost Agency', 'https://seoboost.vn', NULL, '0912345678', 'hello@seoboost.vn', 'Phoebe Dinh', 'Digital marketing and SEO optimization agency.', '2025-11-23 02:20:29'),
(13, 17, 'Helpify Support', 'https://helpify.vn', NULL, '0913456789', 'help@helpify.vn', 'Quentin Lam', 'Remote customer service and support outsourcing team.', '2025-11-23 02:20:29'),
(14, 18, 'OpsMaster Co.', 'https://opsmaster.vn', NULL, '0914567890', 'info@opsmaster.vn', 'Rachel Le', 'Operations management and process optimization consultancy.', '2025-11-23 02:20:29'),
(15, 19, 'TechStart Inc.', 'https://techstart.vn', NULL, '0915678901', 'contact@techstart.vn', 'Sam Nguyen', 'Startup incubator and accelerator for tech entrepreneurs.', '2025-11-23 02:20:29'),
(16, 24, 'VNG', 'https://vng.com.vn/', '/Worknest/public/image/logo/24/logo_1763865587.webp', '123456789', 'thuynguyen@vng.com', 'Thuy Nguyen', 'Công Ty Cổ Phần VNG là một công ty cổ phần được thành lập và hoạt động tại Việt Nam từ ngày 9/9/2004. Kể từ ngày thành lập cho đến nay, Công Ty đã có những bước phát triển vượt bậc, từ một Công Ty chuyên về trò chơi trực tuyến đầu tiên tại Việt Nam (với tên gọi ban đầu là VinaGame), cho đến hiện nay, là một trong những công ty công nghệ, giải trí, dịch vụ internet, dịch vụ tài chính, sản xuất phần mềm hàng đầu tại Việt Nam.', '2025-11-23 02:36:22');

-- Job Categories
INSERT INTO JOB_CATEGORIES (category_name)
VALUES
('Software Engineering'),
('Graphic Design'),
('Marketing'),
('Data Science'),
('Project Management'),
('Human Resources'),
('Education'),
('Customer Support');

-- Jobs (employer_id references EMPLOYERS.id which are auto-generated 1-15)
INSERT INTO `jobs` (`id`, `employer_id`, `posted_by`, `title`, `location`, `description`, `requirements`, `salary`, `deadline`, `status`, `created_at`, `updated_at`, `approved_at`, `rejected_at`) VALUES
(1, 1, 3, 'Backend Developer', 'Ho Chi Minh City', 'Develop REST APIs using Node.js and PostgreSQL.', '3+ years experience with Node.js, Git, Docker.', 25000000.00, '2025-12-31 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-12-01 03:00:00', NULL),
(2, 1, 3, 'Frontend Developer', 'Remote', 'Develop web UIs using React.js.', '2+ years in React, knowledge of Redux.', 22000000.00, '2025-12-20 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-12-01 04:30:00', NULL),
(3, 2, 4, 'UI/UX Designer', 'Da Nang', 'Design modern and intuitive web/mobile interfaces.', 'Experience with Figma, Adobe XD, responsive design.', 18000000.00, '2025-11-30 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-10 02:00:00', NULL),
(4, 2, 4, 'Digital Marketer', 'Hanoi', 'Manage SEO, Google Ads, and content marketing.', '1+ year marketing experience, basic analytics skills.', 15000000.00, '2025-12-15 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-25 07:00:00', NULL),
(5, 3, 5, 'Data Analyst', 'Remote', 'Analyze datasets and create visual dashboards.', 'Proficiency in Python, SQL, Power BI.', 27000000.00, '2025-12-25 23:59:59', 'pending', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, NULL),
(6, 3, 5, 'Project Manager', 'Ho Chi Minh City', 'Lead project execution and cross-team communication.', '5+ years PM experience, PMP certified preferred.', 30000000.00, '2025-11-20 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-05 06:00:00', NULL),
(7, 4, 8, 'Online Instructor', 'Remote', 'Teach computer science topics via EduLink platform.', 'Bachelor in CS, strong communication skills.', 20000000.00, '2025-12-10 23:59:59', 'draft', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, NULL),
(8, 4, 8, 'Customer Support Specialist', 'Hanoi', 'Assist students with technical issues and onboarding.', 'Good English, problem-solving skills.', 12000000.00, '2025-11-25 23:59:59', 'overdue', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-01 01:00:00', NULL),
(9, 5, 9, 'Mobile App Developer', 'Ho Chi Minh City', 'Build Android and iOS apps using Flutter.', '2+ years Flutter experience, REST API integration.', 24000000.00, '2025-12-28 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-12-08 05:00:00', NULL),
(10, 5, 9, 'QA Tester', 'Remote', 'Design and execute test cases for web applications.', 'Experience in manual & automated testing.', 18000000.00, '2025-12-05 23:59:59', 'pending', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, NULL),
(11, 6, 10, 'DevOps Engineer', 'Da Nang', 'Maintain CI/CD pipelines and cloud deployments.', 'Strong Docker, Kubernetes, and AWS skills.', 32000000.00, '2025-12-31 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-12-10 08:00:00', NULL),
(12, 6, 10, 'Security Analyst', 'Hanoi', 'Monitor security threats and perform vulnerability assessments.', 'Knowledge of OWASP, SIEM tools, and incident response.', 28000000.00, '2025-11-28 23:59:59', 'pending', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, NULL),
(13, 7, 11, 'Content Writer', 'Remote', 'Write SEO-friendly articles for tech blogs.', 'Excellent English writing skills, basic SEO knowledge.', 13000000.00, '2025-11-22 23:59:59', 'draft', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, NULL),
(14, 7, 11, 'Graphic Designer', 'Ho Chi Minh City', 'Create marketing materials and social media designs.', 'Proficiency in Photoshop, Illustrator.', 16000000.00, '2025-12-12 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-28 02:30:00', NULL),
(15, 8, 12, 'Network Administrator', 'Hanoi', 'Manage LAN/WAN systems and ensure uptime.', 'Cisco certification preferred, troubleshooting skills.', 25000000.00, '2025-12-18 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-12-01 04:00:00', NULL),
(16, 8, 12, 'Technical Recruiter', 'Remote', 'Source and screen technical candidates for IT roles.', 'Experience with ATS systems and interviewing.', 21000000.00, '2025-12-09 23:59:59', 'pending', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, NULL),
(17, 9, 13, 'Game Developer', 'Ho Chi Minh City', 'Develop mobile games using Unity and C#.', '1+ year Unity experience, teamwork skills.', 23000000.00, '2025-12-30 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-12-07 09:00:00', NULL),
(18, 9, 13, 'Game Designer', 'Remote', 'Design engaging gameplay systems and levels.', 'Creativity, knowledge of game mechanics.', 20000000.00, '2025-12-20 23:59:59', 'pending', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, NULL),
(19, 10, 14, 'AI Researcher', 'Hanoi', 'Develop and train deep learning models for NLP.', 'Strong in Python, TensorFlow/PyTorch, and research papers.', 40000000.00, '2026-01-15 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-12-20 07:00:00', NULL),
(20, 10, 14, 'Data Engineer', 'Ho Chi Minh City', 'Build data pipelines and maintain warehouse systems.', 'Experience in ETL tools, SQL, and Python.', 35000000.00, '2025-12-27 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-12-10 06:00:00', NULL),
(21, 11, 15, 'System Administrator', 'Remote', 'Monitor and maintain servers and network systems.', 'Knowledge of Linux/Windows Server environments.', 26000000.00, '2025-11-29 23:59:59', 'overdue', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-09 03:00:00', NULL),
(22, 11, 15, 'Business Analyst', 'Da Nang', 'Bridge business requirements with technical teams.', 'Analytical skills, UML, and communication abilities.', 28000000.00, '2025-12-22 23:59:59', 'pending', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, NULL),
(23, 12, 16, 'IT Support Specialist', 'Hanoi', 'Provide technical assistance to clients and employees.', 'Customer service mindset, hardware/software troubleshooting.', 15000000.00, '2025-11-26 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-10 02:00:00', NULL),
(24, 12, 16, 'Database Administrator', 'Ho Chi Minh City', 'Manage SQL databases and optimize queries.', '3+ years with MySQL/PostgreSQL.', 30000000.00, '2025-12-17 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-12-01 05:00:00', NULL),
(25, 13, 17, 'Backend Intern', 'Da Nang', 'Assist in backend development tasks using Node.js.', 'Basic JavaScript knowledge, willingness to learn.', 8000000.00, '2025-12-10 23:59:59', 'rejected', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, '2025-11-01 01:00:00'),
(26, 13, 17, 'Product Designer', 'Remote', 'Collaborate with product teams to create user-centered designs.', 'Portfolio with UI/UX examples, Figma expertise.', 19000000.00, '2025-11-18 23:59:59', 'rejected', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, '2025-10-25 07:00:00'),
(27, 14, 18, 'HR Coordinator', 'Hanoi', 'Support recruitment and employee engagement initiatives.', 'Strong organizational and communication skills.', 16000000.00, '2025-11-24 23:59:59', 'soft_deleted', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-05 05:00:00', NULL),
(28, 14, 18, 'Finance Officer', 'Ho Chi Minh City', 'Prepare financial statements and manage transactions.', 'Degree in finance or accounting.', 23000000.00, '2025-12-14 23:59:59', 'soft_deleted', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-20 02:00:00', NULL),
(29, 15, 19, 'Cloud Engineer', 'Remote', 'Deploy and maintain cloud infrastructure on AWS.', 'AWS certification, Terraform, CI/CD knowledge.', 35000000.00, '2026-01-10 23:59:59', 'pending', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, NULL),
(30, 15, 19, 'Cybersecurity Engineer', 'Da Nang', 'Implement and monitor cybersecurity policies.', 'Experience in network security, firewalls, and IDS.', 37000000.00, '2025-12-19 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-28 08:00:00', NULL),
(31, 1, 3, 'Technical Writer', 'Remote', 'Document APIs, SDKs, and developer tools.', 'Excellent writing and technical understanding.', 18000000.00, '2025-12-09 23:59:59', 'approved', '2025-11-23 02:20:29', '2025-11-23 02:20:29', '2025-11-25 03:00:00', NULL),
(32, 2, 4, 'SEO Specialist', 'Hanoi', 'Optimize website content for search engines.', 'Proven SEO track record, keyword research.', 17000000.00, '2025-12-11 23:59:59', 'rejected', '2025-11-23 02:20:29', '2025-11-23 02:20:29', NULL, '2025-11-20 07:00:00'),
(33, 16, 24, 'Intern UI UX', 'Ho Chi Minh City', 'Your job req form should also include a section where managers can include a detailed job description outlining all functions and duties. This will help you understand the added value the potential new hire will bring. It will also serve as the basis of your job postings so that your hiring managers know the type of candidates they should be looking for.\r\n\r\nMake sure your managers are realistic at the requirements of the job and the experience potential candidates will need so that you appeal to a broader range of potential candidates.', 'Prepare Your Case\r\nThe first step before you complete a job req form is preparation.\r\n\r\nWhy do you need to fill a position? Is it down to increased workload or has the focus of your department evolved? How will the new position impact productivity? What about the impact on the rest of the team?\r\n\r\nYour managers need to be able to justify why they need to fill a vacancy or open up a new position. They need to provide clear metrics and goals that will demonstrate how a new employee will help them meet organizational needs and provide a net gain for the business.\r\n\r\nDo Your Research\r\nThe next step involves doing a bit of market research. What skills and experience will you need? What is the going rate for similar positions?\r\n\r\nMake sure your managers research similar ads on job boards to see what candidate expectations are and how they will impact your budget if you take on a new employee.', 15000000.00, '2025-12-06 03:00:00', 'pending', '2025-11-23 02:40:53', '2025-11-23 02:41:02', NULL, NULL),
(34, 16, 24, 'Backend Dev', 'Hanoi', 'Write a Detailed Job Description\r\nYour job req form should also include a section where managers can include a detailed job description outlining all functions and duties. This will help you understand the added value the potential new hire will bring. It will also serve as the basis of your job postings so that your hiring managers know the type of candidates they should be looking for.\r\n\r\nMake sure your managers are realistic at the requirements of the job and the experience potential candidates will need so that you appeal to a broader range of potential candidates.', 'Prepare Your Case\r\nThe first step before you complete a job req form is preparation.\r\n\r\nWhy do you need to fill a position? Is it down to increased workload or has the focus of your department evolved? How will the new position impact productivity? What about the impact on the rest of the team?\r\n\r\nYour managers need to be able to justify why they need to fill a vacancy or open up a new position. They need to provide clear metrics and goals that will demonstrate how a new employee will help them meet organizational needs and provide a net gain for the business.\r\n\r\nDo Your Research\r\nThe next step involves doing a bit of market research. What skills and experience will you need? What is the going rate for similar positions?\r\n\r\nMake sure your managers research similar ads on job boards to see what candidate expectations are and how they will impact your budget if you take on a new employee.', 20000000.00, '2025-12-06 09:41:00', 'rejected', '2025-11-23 02:41:58', '2025-11-23 02:56:20', NULL, '2025-11-22 20:56:20'),
(39, 16, 24, 'Intern Data', 'Hanoi', 'Write a Detailed Job Description\r\nYour job req form should also include a section where managers can include a detailed job description outlining all functions and duties. This will help you understand the added value the potential new hire will bring. It will also serve as the basis of your job postings so that your hiring managers know the type of candidates they should be looking for.\r\n\r\nMake sure your managers are realistic at the requirements of the job and the experience potential candidates will need so that you appeal to a broader range of potential candidates.', 'Prepare Your Case\r\nThe first step before you complete a job req form is preparation.\r\n\r\nWhy do you need to fill a position? Is it down to increased workload or has the focus of your department evolved? How will the new position impact productivity? What about the impact on the rest of the team?\r\n\r\nYour managers need to be able to justify why they need to fill a vacancy or open up a new position. They need to provide clear metrics and goals that will demonstrate how a new employee will help them meet organizational needs and provide a net gain for the business.\r\n\r\nDo Your Research\r\nThe next step involves doing a bit of market research. What skills and experience will you need? What is the going rate for similar positions?\r\n\r\nMake sure your managers research similar ads on job boards to see what candidate expectations are and how they will impact your budget if you take on a new employee.', 20000000.00, '2025-12-05 09:54:00', 'approved', '2025-11-23 02:54:54', '2025-11-23 02:56:09', '2025-11-22 20:56:09', NULL);

-- Job-Category Mapping
INSERT INTO `job_category_map` (`job_id`, `category_id`) VALUES
(1, 1),
(1, 6),
(2, 1),
(3, 2),
(4, 3),
(5, 4),
(6, 5),
(7, 7),
(8, 8),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 2),
(14, 2),
(15, 1),
(16, 6),
(17, 1),
(18, 1),
(19, 4),
(20, 4),
(21, 1),
(22, 5),
(23, 6),
(24, 4),
(25, 1),
(26, 2),
(27, 6),
(28, 5),
(29, 1),
(30, 1),
(31, 1),
(32, 3),
(33, 2),
(33, 3),
(33, 4),
(34, 1),
(34, 4),
(34, 8),
(39, 3),
(39, 4),
(39, 7);

-- Staff Actions
INSERT INTO `staff_actions` (`id`, `user_id`, `job_id`, `action_type`, `action_date`) VALUES
(1, 2, 1, 'Review', '2025-10-01 10:15:00'),
(2, 2, 2, 'Approve', '2025-10-02 14:22:00'),
(3, 7, 3, 'Reject', '2025-10-03 09:00:00'),
(4, 7, 4, 'Review', '2025-10-05 11:45:00'),
(5, 2, 5, 'Update', '2025-10-06 16:30:00'),
(6, 7, 6, 'Approve', '2025-10-07 08:20:00'),
(7, 2, 8, 'Close', '2025-10-08 13:40:00'),
(8, 24, 33, 'job_created', '2025-11-23 03:40:53'),
(9, 24, 33, 'status_changed_to_pending', '2025-11-23 03:41:02'),
(10, 24, 34, 'job_created', '2025-11-23 03:41:58'),
(15, 24, 39, 'job_created', '2025-11-23 03:54:54'),
(16, 24, 39, 'status_changed_to_pending', '2025-11-23 03:55:28'),
(17, 24, 34, 'status_changed_to_pending', '2025-11-23 03:55:31'),
(18, 25, 39, 'job_approved', '2025-11-23 03:56:09'),
(19, 25, 34, 'job_rejected', '2025-11-23 03:56:20');
-- Feedback from users

INSERT INTO FEEDBACK (user_id, comments, created_at)
VALUES
(3, 'The job application process was smooth and intuitive. Great platform!', '2025-10-01 10:15:00'),
(3, 'We should improve moderation speed for new job postings.', '2025-10-02 14:22:00'),
(5, 'Posting jobs was easy, but I’d love to have analytics on applicant activity.', '2025-10-03 09:00:00'),
(8, 'Nice UI, but notifications for applicants could be improved.', '2025-10-05 11:45:00'),
(9, 'Had trouble editing job details after publishing, please check.', '2025-10-06 16:30:00'),
(10, 'Found a great job quickly, thank you for this service!', '2025-10-07 08:20:00'),
(11, 'The admin dashboard works well, but filtering logs could be faster.', '2025-10-08 13:40:00'),
(12, 'EduLink integration looks promising. Keep it up!', '2025-10-09 18:10:00'),
(13, 'Could you add a dark mode option? It would be great for late-night use.', '2025-10-10 22:15:00'),
(14, 'Love the design tools integration with Figma previews.', '2025-10-11 09:55:00');

INSERT INTO `job_reviews` (`id`, `job_id`, `reviewed_by`, `action`, `reason`, `created_at`) VALUES
(1, 1, 2, 'approve', 'Job posting meets all requirements. Approved for public listing.', '2025-10-01 03:00:00'),
(2, 2, 2, 'reject', 'Please include expected salary range and clarify remote policy.', '2025-10-02 02:30:00'),
(3, 3, 7, 'approve', 'Design role verified. Portfolio link provided.', '2025-10-03 08:15:00'),
(4, 4, 7, 'reject', 'Add KPIs or performance metrics to description before approval.', '2025-10-04 04:20:00'),
(5, 5, 2, 'approve', 'Excellent detail on requirements and tools. Approved.', '2025-10-05 06:40:00'),
(6, 6, 7, 'reject', 'Missing PMP certification info, please update.', '2025-10-06 01:30:00'),
(7, 6, 2, 'approve', 'Updated requirements received. Approved for posting.', '2025-10-07 03:45:00'),
(8, 8, 2, 'approve', 'All fields complete. Approved for listing.', '2025-10-08 07:10:00'),
(9, 39, 25, 'approve', 'Very well', '2025-11-23 02:56:09'),
(10, 34, 25, 'reject', 'Not good', '2025-11-23 02:56:20');

UPDATE JOBS SET status = 'approved' WHERE id IN (1, 2, 3, 4, 6);
UPDATE JOBS SET status = 'pending' WHERE id = 5;
UPDATE JOBS SET status = 'draft' WHERE id = 7;
UPDATE JOBS SET status = 'overdue' WHERE id = 8;
