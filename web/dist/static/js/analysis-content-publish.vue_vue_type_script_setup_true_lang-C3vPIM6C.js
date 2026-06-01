import{ae as y,bi as x,aZ as h,aP as k,a5 as _,a2 as s,ab as F,aw as g,bd as E,aV as t,$ as C}from"./index-Ci6OxH_6.js";import{g as w}from"./analysis-CdCWWTj0.js";import{C as $}from"./useEcharts-JID57Q23.js";const A={class:"mine-card w-auto xl:w-8/12"},L={class:"flex items-center justify-between text-base"},V={class:"w-180px"},R=y({__name:"analysis-content-publish",setup(B){const l=t("today"),n=t([]),r=t([]),i=t([]),c=t([]),u=t(),o=x(),{contentPublishRadio:p}=w(),d=p();n.value=d[0].x,d.forEach(e=>{e.name==="纯文本"?r.value=e.y:e.name==="图文类"&&(i.value=e.y),c.value=e.y});function m(e){return e.map(a=>`<div class="content-panel">
      <p>
        <span style="background-color: ${a.color}" class="tooltip-item-icon"></span>
      <span>
        ${a.seriesName}
      </span>
    </p>
    <span class="tooltip-value">
      ${Number(a.value).toLocaleString()}
    </span>
  </div>`).join("")}const v=C(()=>({grid:{left:"4%",right:0,top:"20",bottom:"60"},legend:{bottom:0,icon:"circle",textStyle:{color:"#4E5969"}},xAxis:{type:"category",data:n.value,axisLine:{lineStyle:{color:o.value==="dark"?"#3f3f3f":"#A9AEB8"}},axisTick:{show:!0,alignWithLabel:!0,lineStyle:{color:"#86909C"}},axisLabel:{color:"#86909C"}},yAxis:{type:"value",axisLabel:{color:"#86909C",formatter(e,a){return a===0?`${e}`:`${e/1e3}k`}},splitLine:{lineStyle:{color:o.value==="dark"?"#3F3F3F":"#E5E6EB"}}},tooltip:{show:!0,trigger:"axis",formatter(e){const[a]=e;return`
          <div>
            <p class="tooltip-title">
              ${a.axisValueLabel}
            </p>
            ${m(e)}
          </div>
        `},className:"echarts-tooltip-diy"},series:[{name:"纯文本",data:r.value,stack:"one",type:"bar",barWidth:16,color:o.value==="dark"?"#4A7FF7":"#246EFF"},{name:"图文类",data:i.value,stack:"one",type:"bar",color:o.value==="dark"?"#085FEF":"#00B2FF"},{name:"视频类",data:c.value,stack:"one",type:"bar",color:o.value==="dark"?"#01349F":"#81E2FF",itemStyle:{borderRadius:2}}]}));return $(u).setOption(v.value),(e,a)=>{const b=h("m-tabs");return k(),_("div",A,[s("div",L,[a[1]||(a[1]=s("div",null,"内容发布比例",-1)),s("div",V,[F(b,{modelValue:E(l),"onUpdate:modelValue":a[0]||(a[0]=f=>g(l)?l.value=f:null),class:"h-7 w-full text-sm",options:[{label:"一周内",value:"week"},{label:"昨日",value:"yesterday"},{label:"今日",value:"today"}]},null,8,["modelValue"])])]),s("div",{ref_key:"echarts",ref:u,class:"mt-5 h-300px"},null,512)])}}});export{R as _};
