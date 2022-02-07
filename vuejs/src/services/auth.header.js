export default function authHeader() {
    const user = localStorage.getItem('user')
    if (user) {
        return user;
    } else {
      return null;
    }
}