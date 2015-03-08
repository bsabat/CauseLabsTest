$(function(){

    var Customer = Backbone.Model.extend({
        defaults: function() {
            return {
                id: "",
                nameFirst: "",
                nameLast: "",
                email: "",
                address: "",
                twitter: "",
                facebook: ""
            }
        }
    });


    var CustomerList  = Backbone.Collection.extend({
        model: Customer,
        url: '/customer'
    });
    var Customers = new CustomerList();


    var CustomerDetails = Backbone.View.extend({
        el: '#customer-details-modal',

        events: {
            'click .save' : 'save',
            'click .cancel': 'cancel',
            'click .delete': 'delete'
        },

        initialize: function() {
            this.listenTo(this.model, 'change', this.render);
        },

        render: function() {
            if(this.model != null) {
                this.$el.modal({show:true});


            }
            return this;
        },

        save: function() {
            this.model.save();
            this.reset();
            return this;

        },

        cancel: function(){
            this.reset();
        },

        delete: function(){
            this.model.destroy();
            this.reset();
            return this;
        },

        reset: function() {
            this.$el.modal({show: false});
            this.model = null;
            $('input').each(function(i) {
                $(this).val("");
            });

            return this;
        }


    });

    var CustomerListItemView = Backbone.View.extend({
        tagNasme: "li",
        template: _.template($('#item-template').html()),

        events: {
            'click .edit': 'edit'
        },

        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.remove);
        },

        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },

        edit: function () {
            customerDetails = new CustomerDetails({model: this.model});
            customerDetails.render();
            return this;
        }
    });


    var CustomerAppView = Backbone.View.extend({
        el: "#customer-app",

        events: {
            'click #new-button' : 'newCustomer'
        },

        initialize: function() {
            this.listenTo(Customers, 'add', this.addOne);
            this.listenTo(Customers, 'reset', this.addAll);
            this.listenTo(Customers, 'all', this.render);
        },

        render: function() {

        },

        addOne: function(customer) {
            var view = new CustomerListItemView({model: customer});
            this.$("#customer-list").append(view.render().el);
        },

        addAll: function() {
            $("#customer-list").html("");
            Customers.each(this.addOne, this);
        },

        newCustonmer: function() {

        }
    });

    var app = new CustomerAppView;
});