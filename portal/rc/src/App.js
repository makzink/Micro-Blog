import React, { Component } from 'react';
import './App.css';
import NavBar from './components/nav_bar';
import Footer from './components/footer';
import Blog from './components/blog/blog';

class App extends Component {
  render() {
    return (
        <div>
            <NavBar/>

            <Blog/>

            <Footer/>
        </div>
    );
  }
}

export default App;
