// JavaScript Document

// ツールチップ
function Tooltip()
{
    // 内容
    this.content = document.createElement( 'div' );
    this.content.className = 'tooltip-content';

    // 影
    this.shadow = document.createElement( 'div' );
    this.shadow.className = 'tooltip-shadow';

    this.shadow.appendChild( this.content );
}

Tooltip.DELAY = 0;    // 表示するまでの遅延時間
Tooltip.OFFSET = 5;

// ツールチップを表示する
Tooltip.prototype.Show = function( text, x, y )
{
    // 内容
    while( this.content.hasChildNodes() )
    {
        this.content.removeChild( this.content.lastChild );
    }
    this.content.appendChild( document.createTextNode( text ) );

    // 影
    this.shadow.style.left = x + 'px';
    this.shadow.style.top = y + 'px';
    this.shadow.style.visibility = 'visible';


    if( this.shadow.parentNode != document.body )
    {
        // ドキュメントのbody要素に追加する
        document.body.appendChild( this.shadow );
    }
}

// ツールチップを隠す
Tooltip.prototype.Hide = function()
{
    this.shadow.style.visibility = 'hidden';
}
// ツールチップの表示を予定する
Tooltip.prototype.Schedule = function( targetElement, event )
{
    var e = event || window.event;

    var x = e.clientX;
    var y = e.clientY;

    // マウスポインタの位置をドュメント座標に正する
    x += window.pageXOffset || document.documentElement.scrollLeft;
    y += window.pageYOffset || document.documentElement.scrollTop;

    // イベントハンドラ内で処理できthisンす
    var _this = this;


    // タイムアウト処理を設定する
    var timerID = window.setTimeout(
        function()
        {
            var text = targetElement.getAttribute( 'tooltip' );

            // ツールチップを示す
            _this.Show(
                text,
                x + Tooltip.OFFSET,
                y + Tooltip.OFFSET
                );
        },
        Tooltip.DELAY
        );


    function MouseOut()
    {
        // ツールチップを隠す
        _this.Hide();

        // 未処理のタイムアウト処理を取消す
        window.clearTimeout( timerID );

        // イベントハンドラを削除する
        if( targetElement.removeEventListener )
        {
            targetElement.removeEventListener( 'mouseout', MouseOut, false );
        }
        else
        {
            // IE用の処理
            targetElement.detachEvent( 'onmouseout', MouseOut );
        }
    }

    // イベントハンドラを登録する
    if( targetElement.addEventListener )
    {
        targetElement.addEventListener( 'mouseout', MouseOut, false );
    }
    else
    {
        // IE用の処理
        targetElement.attachEvent( 'onmouseout', MouseOut );
    }
}
var tooltip = new Tooltip();