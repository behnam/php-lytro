(function($){
    function Lytro(container)
    {
        this.container = $(container);
        this.picture = this.container.find('.picture');
        this.current = 0;
        this.loading = 0;
        this.zindex = 20;
        this.images = [];
        this.data = {};

        var image = this.container.find('img');
        image.addClass('current');
        image.hide();

        this.images.push(image);
        this.bindEvents();
        this.load();
    };

    Lytro.prototype.bindEvents = function()
    {
        var self = this;

        this.picture.bind('click', function(e){
            if (this.loading) {
                return;
            }

            var mouseX = e.pageX - $(this).offset().left;
            var mouseY = e.pageY - $(this).offset().top;
            var focus = self.translateFocus(mouseX, mouseY);

            if (typeof(focus) == 'undefined') {
                return;
            }

            self.changeFocus(focus);
        });
    };

    Lytro.prototype.changeFocus = function(depth)
    {
        var focus = 0;
        var index = 0;

        for (var i in this.data['images']) {
            var image = this.data['images'][i];

            if (Math.abs(image.focus) > focus && image.focus <= depth) {
                focus = image.focus;
                index = i;
            }
        }

        for (var i = 0; i < this.images.length; i++) {
            var image = $(this.images[i]);

            if (i == index) {
                if (!image.hasClass('current')) {
                    image.css('z-index', this.zindex++);
                    image.hide();
                    image.fadeIn();
                }

                image.addClass('current');
            } else {
                image.removeClass('current');
            }
        }
    };

    Lytro.prototype.translateFocus = function(x, y)
    {
        var size = this.data.dimensions.width;
        var delta = this.data.size / size;

        var pointX = Math.floor((x * delta) / ((this.data.size * delta) / 20));
        var pointY = Math.floor((y * delta) / ((this.data.size * delta) / 20));
        var focus = pointY * 20 + pointX;

        return this.data['lookup'][focus];
    };

    Lytro.prototype.init = function()
    {
        var self = this;

        this.loading = this.data.images.length;

        for (var i = 0; i < this.loading; i++) {
            var image = $('<img />').attr('src', 'image.php?ref=' + this.data.images[i].ref);
            image.css('z-index', 10);
            image.hide();
            image.bind('load', function(){
                self.imageLoaded();
            });

            this.picture.append(image);
            this.images.push(image);
        }
    };

    Lytro.prototype.imageLoaded = function()
    {
        this.loading--;

        if (this.loading == 0) {
            this.images[this.current].fadeIn();
        }
    };

    Lytro.prototype.load = function()
    {
        var self = this;

        $.getJSON('get_json.php', function(json){
            self.data = json;
            self.init();
        });
    };

    // Load mini-lytro player.
    $(function(){
        $('.lytro').each(function(){
            new Lytro(this);
        })
    });
})(jQuery);