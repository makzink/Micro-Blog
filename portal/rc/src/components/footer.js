import React from 'react';
import '../../node_modules/bootstrap/dist/css/bootstrap.min.css';

class Footer extends React.Component {

    render(){

        const footer_style = {
            marginTop: '3em',
            backgroundColor: '#f8f8f8',
            height: '5em'
        }

        return(
            <div className="container-fluid" style={footer_style}>

            </div>
        )
    };

}

export default Footer;
