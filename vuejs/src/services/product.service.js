import axios from 'axios';
import authHeader from './auth.header';

const API_URL = 'http://localhost:8080/api/test/';

class ProductService {
  addProduct(data) {
    for (var key of data.entries()) {
      console.log(key[0] + ' ==> ' + key[1]);
  }
    axios({
        method: "post",
        url: API_URL,
        data: data,
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then(function (response) {
          //handle success
          console.log(response);
        })
        .catch(function (response) {
          //handle error
          console.log(response);
        });
  }

  getUserBoard() {
    return axios.get(API_URL + 'user', { headers: authHeader() });
  }

  getPizzeriaBoard() {
    return axios.get(API_URL + 'mod', { headers: authHeader() });
  }

  getAdminBoard() {
    return axios.get(API_URL + 'admin', { headers: authHeader() });
  }
}

export default new ProductService();