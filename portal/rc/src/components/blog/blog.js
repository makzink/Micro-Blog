import React from 'react';
import '../../../node_modules/bootstrap/dist/css/bootstrap.min.css';
import './blog.css';
import fontawesome from '@fortawesome/fontawesome'
import FontAwesomeIcon from '@fortawesome/react-fontawesome'
import faEye from '@fortawesome/fontawesome-free-solid/faEye'
import faHeart from '@fortawesome/fontawesome-free-solid/faHeart'
import { Modal } from 'react-bootstrap';
import axios from 'axios';

class Blog extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            'b_sort' : '1',
            'b_topic' : '0',
            'loading' : true,
            'content' : [],
            'read_blog' : false,
            'blog_loading' : true,
            'blog_content' : {}
        }
        this.fetch_blog = this.fetch_blog.bind(this);
        this.topicChange = this.topicChange.bind(this);
        this.sortChange = this.sortChange.bind(this);
        this.readBlog = this.readBlog.bind(this);
        this.handleClose = this.handleClose.bind(this);
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
            this.setState({'loading':false,'content':response.data.content});
        }.bind(this));
    }

    topicChange(e){
        this.setState({b_topic: e.target.value},()=>{this.fetch_blog();});
    }

    sortChange(e){
        this.setState({b_sort: e.target.value},()=>{this.fetch_blog();});
    }

    readBlog(article_id){
        this.setState({'read_blog':true,'blog_loading': true});
        axios({
            method: 'post',
            url: 'http://api.kazmik.in/php/blog/read/',
            data: {
                article_id: article_id,
            }
        }).then(function(response) {
            this.setState({'blog_loading':false,'blog_content':response.data.content});
        }.bind(this));
    }

    handleClose() {
        this.setState({ 'read_blog' : false });
    }

    componentDidMount() {
        this.fetch_blog();
    }

    render(){

        fontawesome.library.add(faEye, faHeart);

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

                {( this.state.loading === true ) ?
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
                    <BlogList content={this.state.content} readBlog={this.readBlog}/>
                }


                    <Modal show={this.state.read_blog} onHide={this.handleClose} animation={false} id="detail_modal">
                        <Modal.Header closeButton>
                        </Modal.Header>
                        <Modal.Body>

                            {( this.state.blog_loading === false ) ?

                                <div className="dm_body">
                                    <div className="clearfix">
                                        <img className="dm_img" src={this.state.blog_content.img}/>
                                    </div>
                                    <div className="clearfix">
                                        <div className="dm_title">{this.state.blog_content.title}</div>
                                    </div>
                                    <div className="clearfix">
                                        <div className="dm_cat">{this.state.blog_content.category}</div>
                                        <div className="dm_date">{this.state.blog_content.date}</div>
                                    </div>
                                    <div className="clearfix dm_data">
                                        <div className="stats">
                                            <span className="views"><FontAwesomeIcon icon="eye" /><span className="views_c"> {this.state.blog_content.views}</span></span>
                                            <span className="likes"><FontAwesomeIcon icon="heart" /><span className="likes_c"> {this.state.blog_content.likes_c}</span></span>
                                        </div>
                                        <div className="dm_auth">
                                            <span className="dm_auth_name">{this.state.blog_content.auth_usr}</span>
                                            <img className="dm_auth_img" src="http://medondoor.com/wp-content/themes/health/img/placeholder.png"/>
                                        </div>
                                    </div>
                                    <div className="clearfix">
                                        <div className="dm_content" dangerouslySetInnerHTML={{__html: this.state.blog_content.content}}></div>
                                    </div>
                                </div>

                            :
                                <center>
                                    <div className="hush_loading_wrapper">
                                        <div className="hush_loading hidden">
                                            <svg className="circle-loader" width="40" height="40" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="20" cy="20" r="15"/>
                                            </svg>
                                        </div>
                                    </div>
                                </center>
                            }
                        </Modal.Body>
                    </Modal>
            </div>
        )
    };

}

function BlogList(props)
{
    const content = props.content;

    const listItems = content.map((blog_card) =>
        <div className="b_card hidden" key={blog_card.article_id} id="b_card" onClick={() => props.readBlog(blog_card.article_id)}>
            {/*}
            <div className="b_like">
                <span className="toggle-icon" title="Like Blog"></span>
            </div>
            */}
            <div className="clearfix">
                <img className="b_img" src={blog_card.img} alt="Blog"/>
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
                    <img className="b_auth_img" src="http://medondoor.com/wp-content/themes/health/img/placeholder.png" alt="User"/>
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
