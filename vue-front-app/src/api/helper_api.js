import axios from 'axios';

export const API_BASE_URL = 'http://localhost:8080/api';

export const API_Helper = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        "Content-type": "application/json; charset=UTF-8"
    }
});
