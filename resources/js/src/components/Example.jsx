import React from 'react';
import ReactDOM from 'react-dom';
import Call from './Call';

function Example() {
    return (
        <div></div>
    );
}

export default Example;

if (document.getElementById('example')) {
    ReactDOM.render(<Example />, document.getElementById('example'));
}

