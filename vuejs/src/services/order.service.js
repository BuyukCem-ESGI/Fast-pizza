import axios from 'axios';
import authHeader from './auth.header';

const API_URL = 'https://localhost:443';

class OrderService {

    addOrder(data) {
        console.log(data);
        axios.post(API_URL + "/order", {
            data
        },{
            headers: {'Authorization': "Bearer "+authHeader()}
        }).then(function (response) {
            //handle success
            console.log(response);
        }).catch(function (response) {
            //handle error
            console.log(response);
        });
    }

    getAllOrder() {
      return axios.get(API_URL + '/order',{headers: {'Authorization': "Bearer "+authHeader()}});
    }

}

export default new OrderService();
