<?php global $teo_data; ?>
<script id="article-template" type="text/x-handlebars-template">
    <div class="homepage-post">
        <figure>
            <div class="image-overlay">
                <img src="{{image}}" alt="{{title}}"/>
            </div>
            <div class="overlay">
                <div class="inner">
                    <div class="figure-text">
                        <h3><a href="{{link}}">{{{title}}}</a></h3>
                        <hr class="hidden-xs"/>
                        <h5 class="hidden-xs"><a href="{{authorLink}}">{{author}}</a> / <a href="{{dateLink}}">{{date}}</a>  / {{{category}}}</h5>
                    </div>
                </div>
            </div>
        </figure>
        <p>
            {{excerpt}}
        </p>
        <div class="info post-share">
            <a href="{{link}}#comments" class="text">
                <i class="icon-comment"></i>
                {{commentCount}}
            </a>
            <a  href="{{link}}" class="text">
                <i class="icon-heart"></i>
                {{heartCount}}
            </a>
            <?php if(isset($teo_data['show_share']) && $teo_data['show_share'] == 1) { ?>
            <div class="dropdown">
                <a class="text" data-toggle="dropdown" href="#">
                    <i class="icon-share"></i>
                    <?php _e('Share', 'arwyn');?>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <li><a href="javascript:void(0)" data-href="https://www.facebook.com/sharer/sharer.php?u={{link}}"><i class="icon-facebook"></i></a></li>
                    <li><a href="javascript:void(0)" data-href="http://reddit.com/submit?url={{link}}&amp;title={{title}}"><i class="icon-reddit"></i></a></li>
                    <li><a href="javascript:void(0)" data-href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text={{title}}&url={{link}}"><i class="icon-twitter"></i></a></li>
                    <li><a href="javascript:void(0)" data-href="https://plus.google.com/share?url={{link}}"><i class="icon-googleplus icon-smaller"></i></a></li>
                    <li><a href="javascript:void(0)" data-href="http://www.stumbleupon.com/submit?url={{link}}&amp;title={{title}}"><i class="icon-stumbleupon icon-smaller"></i></a></li>
                    <li><a class="dohref" href="mailto:?Subject={{title}}&amp;Body=<?php _e('Check this great post', 'arwyn'); echo ' ';?> {{link}}"><i class="icon-mail icon-smaller"></i></a></li>
                </ul>
            </div>
            <?php } ?>
        </div>
    </div>
</script>