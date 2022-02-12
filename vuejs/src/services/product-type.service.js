import axios from 'axios';
import authHeader from './auth.header';

const API_URL = 'https://localhost:443';

class ProductTypeService {

    addProductType(data) {
        console.log(data);
        axios.post(API_URL + "/product-type", {
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
    updateProduct(data,id) {
        console.log(data);
        var config = {
            method: 'patch',
            url: API_URL+'/product-type/'+id,
            headers: {'Authorization': "Bearer "+authHeader()},
            data
          };
          
        return  axios(config)
    }

    getProductById(id) {
      var config = {
        method: 'get',
        url: API_URL + '/product-type/'+id,
        headers: {'Authorization': "Bearer "+authHeader()},
      };
        return axios(config)
    }

    getAllProducts() {
      return axios.get(API_URL + '/product-type',{headers: {'Authorization': "Bearer "+authHeader()}});
    }

    deleteProduct(id) {
        var config = {
            method: 'delete',
            url:  API_URL+'/product-type/'+id,
            headers: { 
              'Content-Type': 'application/json'
            }
          };
          
        return axios(config)
    }
}

export default new ProductTypeService();
