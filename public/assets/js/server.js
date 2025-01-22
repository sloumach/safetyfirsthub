const express = require('express');
const multer = require('multer');
const path = require('path');
const app = express();

// Set up storage for uploaded files
const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        cb(null, 'uploads/'); // Save files in the 'uploads' folder
    },
    filename: function (req, file, cb) {
        const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1e9);
        cb(null, uniqueSuffix + path.extname(file.originalname)); // Unique filename
    }
});

// File filter to allow only images
const fileFilter = (req, file, cb) => {
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (allowedTypes.includes(file.mimetype)) {
        cb(null, true);
    } else {
        cb(new Error('Invalid file type. Only JPEG, PNG, and GIF are allowed.'));
    }
};

const upload = multer({
    storage: storage,
    fileFilter: fileFilter,
    limits: { fileSize: 5 * 1024 * 1024 } // Limit file size to 5MB
});

// Serve static files (e.g., uploaded avatars)
app.use('/uploads', express.static('uploads'));

// Middleware to parse JSON and URL-encoded form data
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Handle profile update
app.post('/update-profile', upload.single('avatar'), (req, res) => {
    try {
        const { fullName, jobRole, email, phone, company, website, address, city, postalCode, country } = req.body;

        // Validate required fields
        if (!fullName || !email || !phone) {
            return res.status(400).json({ success: false, message: 'Full Name, Email, and Phone Number are required.' });
        }

        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return res.status(400).json({ success: false, message: 'Invalid email format.' });
        }

        // Construct avatar URL if a file was uploaded
        const avatarUrl = req.file ? `/uploads/${req.file.filename}` : null;

        // Save the data to your database (this is a mock example)
        // Replace this with your actual database logic
        console.log('Saving profile data:', {
            fullName,
            jobRole,
            email,
            phone,
            company,
            website,
            address,
            city,
            postalCode,
            country,
            avatarUrl
        });

        // Respond with success and the new avatar URL
        res.json({ success: true, avatarUrl });
    } catch (error) {
        console.error('Error updating profile:', error);
        res.status(500).json({ success: false, message: 'An error occurred while updating the profile.' });
    }
});

// Error handling middleware
app.use((err, req, res, next) => {
    if (err instanceof multer.MulterError) {
        // Handle Multer errors (e.g., file size limit exceeded)
        res.status(400).json({ success: false, message: err.message });
    } else if (err) {
        // Handle other errors
        res.status(500).json({ success: false, message: err.message });
    }
});

app.listen(3000, () => {
    console.log('Server running on http://localhost:3000');
});