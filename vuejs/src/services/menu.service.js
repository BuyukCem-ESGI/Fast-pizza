import axios from 'axios';
import authHeader from './auth.header';

const API_URL = 'https://localhost:443';

class MenuService {
    addMenus(data) {
        return axios.post(API_URL + "/menus", data,{
            headers: {'Authorization': "Bearer "+authHeader()}
        })
    }

    updateMenus(data,id) {
        console.log(data);
        return axios({
            method: 'patch',
            url: API_URL+'/menus/'+id,
            headers: {'Authorization': "Bearer "+authHeader()},
            data
        });
    }

    getMenusById(id) {
      const config = {
        method: 'GET',
        url: API_URL + '/menus/'+id,
        headers: {'Authorization': "Bearer "+authHeader()},
      };
        return axios(config)
    }

    getAllMenus() {
      return axios.get(API_URL + '/menus',{headers: {'Authorization': "Bearer "+authHeader()}});
    }

    deleteMenus(id) {
        return axios( {
            method: 'menus',
            url:  API_URL+'/products/'+id,
            headers: {
                'Authorization': "Bearer "+authHeader()
            }
        })
    }
}

export default new MenuService();
