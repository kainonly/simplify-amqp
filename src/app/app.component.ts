import {Component, OnInit} from '@angular/core';
import {NzTreeNodeOptions} from 'ng-zorro-antd';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
})
export class AppComponent implements OnInit {
  nodes: NzTreeNodeOptions[] = [{
    title: '腾讯云',
    key: '1',
    type: 'project',
    children: [{
      title: 'db0',
      key: 'db0',
      type: 'database',
      children: [{
        title: 'admin-hash',
        key: '11',
        isLeaf: true
      }, {
        title: 'role-hash',
        key: '12',
        isLeaf: true
      }]
    }, {
      title: 'db1',
      key: 'db1',
      type: 'database',
    }]
  }, {
    title: '阿里云',
    key: '2',
    type: 'project',
  }];

  ngOnInit() {
  }
}
