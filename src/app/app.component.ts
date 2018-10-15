import {Component} from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
})
export class AppComponent {
  dataSet = [
    {
      type: '字符串',
      key: 'admin_token',
    },
    {
      type: '哈希',
      key: 'admin_lists',
    },
    {
      type: '集合',
      key: 'express_order',
    },
    {
      type: '组',
      key: 'admin'
    },
  ];

  editorOptions = {theme: 'vs-dark', language: 'javascript'};
  code = 'function x() {\nconsole.log("Hello world!");\n}';

}
