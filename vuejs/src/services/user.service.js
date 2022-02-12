import axios from 'axios';
import authHeader from './auth.header';

const API_URL = 'https://localhost:443';

class UserService {

    updateUser(data,id) {
        console.log(data);
        var config = {
            method: 'patch',
            url: API_URL+'/users/'+id,
            headers: { 
              'Authorization': "Bearer "+authHeader()
            },
            data
          };
          
        return  axios(config)
    }

    getUserById(id) {
        return axios.get(API_URL + '/users/'+id,{headers: {'Authorization': "Bearer "+authHeader()}})
    }

    deleteUser(id) {
        var config = {
            method: 'delete',
            url:  API_URL+'/users/'+id,
            headers: { 
              'Authorization': "Bearer "+authHeader()
            }
          };
          
        return axios(config)
    }
}

export default new UserService();