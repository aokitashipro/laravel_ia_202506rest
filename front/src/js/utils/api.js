const API_BASE_URL = 'http://localhost:8000/api'

export const apiClient = {
    async get(endpoint) {
        const token = localStorage.getItem('token')
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            headers: {
                'Authorization': token ? `Bearer ${token}` : '',
                'Content-Type': 'application/json'
            }
        })
        
        const data = await response.json()
        
        if (!response.ok) {
            throw data // エラーレスポンスをthrow
        }
        
        return data
    },
    
    async post(endpoint, data) {
        const token = localStorage.getItem('token')
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            method: 'POST',
            headers: {
                'Authorization': token ? `Bearer ${token}` : '',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        
        const responseData = await response.json()
        
        if (!response.ok) {
            throw responseData // エラーレスポンスをthrow
        }
        
        return responseData
    },
    
    async put(endpoint, data) {
        const token = localStorage.getItem('token')
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            method: 'PUT',
            headers: {
                'Authorization': token ? `Bearer ${token}` : '',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        
        const responseData = await response.json()
        
        if (!response.ok) {
            throw responseData
        }
        
        return responseData
    },
    
    async delete(endpoint) {
        const token = localStorage.getItem('token')
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            method: 'DELETE',
            headers: {
                'Authorization': token ? `Bearer ${token}` : '',
                'Content-Type': 'application/json'
            }
        })
        
        const responseData = await response.json()
        
        if (!response.ok) {
            throw responseData
        }
        
        return responseData
    }
}