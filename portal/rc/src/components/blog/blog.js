import React from 'react';
import '../../../node_modules/bootstrap/dist/css/bootstrap.min.css';
import './blog.css';
import axios from 'axios';

class Blog extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            'b_sort' : '1',
            'b_topic' : '0',
            'loading' : true,
            'content' : [],
        }
        this.fetch_blog = this.fetch_blog.bind(this);
        this.topicChange = this.topicChange.bind(this);
        this.sortChange = this.sortChange.bind(this);
    }

    fetch_blog(){
        this.setState({'loading':true});
        axios({
            method: 'post',
            url: 'http://api.kazmik.in/php/blog/fetch/',
            data: {
                b_sort: this.state.b_sort,
                b_topic: this.state.b_topic
            }
        }).then(function(response) {
            console.log(response.data.content);
            this.setState({'loading':false,'content':response.data.content});
        }.bind(this));
    }

    topicChange(e){
        this.setState({b_topic: e.target.value},()=>{this.fetch_blog();});
    }

    sortChange(e){
        this.setState({b_sort: e.target.value},()=>{this.fetch_blog();});
    }

    componentDidMount() {
        this.fetch_blog();
    }

    render(){

        return(
            <div className="container blog_container">

                <div className="blog_options">

                    <div className="f_type pull-right b_opt">
                        <div className="f10">Filter by :</div>
                        <div className="form-group">
                            <select className="form-control" id="b_topic" value={this.state.b_topic} onChange={this.topicChange}>
                                <option value="0">All</option>
                                <option value="1">Career</option>
                                <option value="2">Culture</option>
                                <option value="3">Compensation</option>
                            </select>
                        </div>
                    </div>

                    <div className="f_type pull-right b_opt">
                        <div className="f10">Sort by :</div>
                        <div className="form-group">
                            <select className="form-control" id="b_sort" value={this.state.b_sort} onChange={this.sortChange}>
                                <option value="1">Most Popular</option>
                                <option value="2">Latest</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div className="clearfix"></div>

                {( this.state.loading == true ) ?
                    <center>
                        <div className="hush_loading_wrapper">
                            <div className="hush_loading hidden">
                                <svg className="circle-loader" width="40" height="40" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="20" cy="20" r="15"/>
                                </svg>
                            </div>
                        </div>
                    </center>
                :
                    <BlogList content={this.state.content} />
                }


            </div>
        )
    };

}

function BlogList(props)
{
    const content = props.content;
    const listItems = content.map((blog_card) =>
        <div className="b_card hidden" id="b_card">
            <div className="b_like">
                <span className="toggle-icon" title="Like Blog"></span>
            </div>
            <div className="clearfix">
                <img className="b_img" src={blog_card.img}/>
            </div>
            <div className="clearfix">
                <div className="b_cat">{blog_card.category}</div>
            </div>
            <div className="clearfix">
                <div className="b_title">{blog_card.title}</div>
            </div>
            <div className="footer clearfix">
                <div className="b_readon">READ ON</div>
                <div className="b_auth">
                    <span className="b_auth_name">{blog_card.auth_usr}</span>
                    <img className="b_auth_img" src="http://medondoor.com/wp-content/themes/health/img/placeholder.png"/>
                </div>
                <div className="clearfix"></div>
                <div className="b_bar"></div>
            </div>
        </div>
    );
    return (
        <div className="blog_list">{listItems}</div>
    );
};


export default Blog;
