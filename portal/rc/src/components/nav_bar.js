import React from 'react';
import cookie from 'react-cookies'
import '../../node_modules/bootstrap/dist/css/bootstrap.min.css';

class NavBar extends React.Component {

    login_signup(mode) {
        console.log("login signup "+mode);
    }

    logout_user(){
        console.log("logout user");
    }

    render(){
        const navbar_style = {backgroundcolor: '#f8f8f8'};
        const usr_token = cookie.load('_hust_ut');

        const nav_btn = ((usr_token === undefined)?1:0) ? (
            <ul className="nav navbar-nav navbar-right">
                {/*
                <li onClick={() => this.login_signup(1)}><a href="#"><span className="glyphicon glyphicon-user"></span> Sign Up</a></li>
                <li onClick={() => this.login_signup(2)}><a href="#"><span className="glyphicon glyphicon-log-in"></span> Login</a></li>
                */}
            </ul>
        ) : (
            <ul className="nav navbar-nav navbar-right">
                {/*
                <li onClick={this.logout_user}><a href="#"><span className="glyphicon glyphicon-log-out"></span> Logout</a></li>
                */}
            </ul>
        );

        return(
            <nav className="navbar navbar-default navbar-light bg-light" style={navbar_style}>
                <div className="container-fluid">
                    <div className="navbar-header">
                        <a className="navbar-brand" href="/">Kazmik Corp.</a>
                    </div>
                    {nav_btn}
                </div>
            </nav>
        )
    };

}

export default NavBar;
