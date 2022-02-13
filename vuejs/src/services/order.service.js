import axios from 'axios';
import authHeader from './auth.header';

const API_URL = 'https://localhost:443';

class OrderService {

    addOrder(data) {
        console.log(data);
        return axios.post(API_URL + "/orders",data,{
            headers: {'Authorization': "Bearer "+authHeader()}
        })
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

    changeOrderStatus(data) {
        return axios.patch(API_URL + '/orders/'+data,{"status": "READY"},
            {headers: {'Authorization': "Bearer "+authHeader()}});
    }
}

export default new OrderService();
