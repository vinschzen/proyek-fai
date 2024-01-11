
const axios = require('axios');

module.exports = async (req, res) => {
    try {
        const response = await axios.post('https://proyek-fai-t4z6-azure.vercel.app/callback', req.body);

        console.log('Response from Laravel:', response.data);

        res.status(200).send('OK');
    } catch (error) {
        console.error('Error forwarding request to Laravel:', error.message);
        res.status(500).send('Internal Server Error');
    }
};
