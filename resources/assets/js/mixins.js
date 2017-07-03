module.exports = {
    methods: {
        highlightText: function (index, text_lenght, opened, closed, value) {
         	return value.substring(0,index) + opened + value.substring(index, index + text_lenght) + closed + value.substring(index + text_lenght);   
        }
    }
}