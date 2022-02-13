import axios from 'axios';
import authHeader from './auth.header';

const API_URL = 'https://localhost:443';

class OrderService {

    addOrder(data) {
        console.log(data);
        axios.post(API_URL + "/orders",data,{
            headers: {'Authorization': "Bearer "+authHeader()}
        }).then(function (response) {
            //handle success
            console.log(response);
        }).catch(function (response) {
            //handle error
            console.log(response);
        });
    }

    getAllOrders() {
      return axios.get(API_URL + '/order',{headers: {'Authorization': "Bearer "+authHeader()}});
    }

    getUserOrders() {
        return axios.get(API_URL + '/orders',{headers: {'Authorization': "Bearer "+authHeader()}});
    }

    getPizzeriaOrders() {
        return axios.get(API_URL + '/orders?page=1&deliveryStatus="PROGRESS"',{headers: {'Authorization': "Bearer "+authHeader()}});
    }

    changeOrderStatus() {
       
    }


    getNotReadyOrders

}

export default new OrderService();
