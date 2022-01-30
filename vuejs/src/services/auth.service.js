import axios from 'axios';

class AuthService {
    login(user) {
        console.log("signin ", user)
        return new Promise((resolve, reject) => {
             axios
            .post("https://localhost:443/authentication_token", {
                email: user.email,
                password: user.password
            })
            .then(response => {
                if (response.status === 200) {
                    localStorage.setItem("user", JSON.stringify(response.data));
                    resolve({message: "Success"});
                }
                if (response.status === 401) {
                    reject({message: "Invalid email or password"})
                }
            }).catch(() => {
               reject({message: "Invalid email or password"})
            });
        });
    }
    logout() {
        localStorage.removeItem('user');
    }

    register(user) {
        console.log("register ++")
        console.log(user)
        console.log('/auth/register')

        return axios.post('https://localhost:443/auth/register', {
            email: user.email,
            firstname: user.firstName,
            lastname: user.lastName,
            phoneNumber: user.phoneNumber,
            password: user.password
        }).then(() => {
            return "Register success";
        }).catch(() => {
            return "Error when registering";
        });
    }
}

export default new AuthService();
