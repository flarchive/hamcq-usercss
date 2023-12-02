import app from 'flarum/forum/app';
import UserPage from 'flarum/components/UserPage';
import { extend } from 'flarum/common/extend';
import SettingsPage from 'flarum/common/components/SettingsPage';
import FieldSet from 'flarum/common/components/FieldSet';
import ItemList from 'flarum/common/utils/ItemList';
import Switch from 'flarum/common/components/Switch';

app.initializers.add('hamcq/usercss', () => {
  extend(UserPage.prototype, 'oncreate', function () { 
    if(!this.user.data.attributes.myStyleEnable){
      return;
    }
    var new_element=document.createElement("link");
    new_element.setAttribute("id","my_style");
    new_element.setAttribute("rel","stylesheet");
    new_element.setAttribute("type","text/css");
    new_element.setAttribute("href",app.forum.attribute('apiUrl')+"/my_style/"+this.user.data.id);
    document.body.appendChild(new_element);
  })

  extend(UserPage.prototype, 'onremove', function () { 
    var e = document.getElementById("my_style");
    if(!e){
      return;
    }
    e.remove();
  })

  extend(SettingsPage.prototype, 'settingsItems', function (items) {
    items.add(
      'userPageCss',
      FieldSet.component(
        {
          label: "自定义 CSS",
          className: '',
        },
        this.cssItems().toArray()
      )
    );
  });

  SettingsPage.prototype['cssItems'] = function () {
    const items = new ItemList();
    items.add(
      'myStyle-enable',
      Switch.component(
        {
          state: this.user.preferences().myStyleEnable,
          onchange: (value) => {
            this.myStyleEnableLoading = true;

            this.user.savePreferences({ myStyleEnable: value }).then(() => {
              this.myStyleEnableLoading = false;
              m.redraw();
            });
          },
          loading: this.myStyleEnableLoading,
        },
        "使用自定义 CSS"
      )
    );

    items.add(
      'css-enable',
      <textarea 
        className="FormControl" 
        style="width:380px" 
        disabled={this.disabled}
        onchange={e => this.myStyle = e.target.value}
        onblur={()=>{
          this.disabled = true;
          this.user.savePreferences({ myStyle: this.myStyle }).
            then(() => {
              m.redraw();
            });
            this.disabled = false;
        }}
        rows="10">{this.user.preferences().myStyle}</textarea>
    );
    return items;
  }
});
