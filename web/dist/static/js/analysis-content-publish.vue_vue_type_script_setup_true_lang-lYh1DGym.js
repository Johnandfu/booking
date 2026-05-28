import{d as b,r as a,af as x,c as h,e as k,f as _,h as F,j as s,b as g,ab as E,O as C}from"./index-P7y_Ssvu.js";import{g as w}from"./analysis-BwWLRqIc.js";import{C as A}from"./useEcharts-DVZjSHu_.js";const L={class:"mine-card w-auto xl:w-8/12"},$={class:"flex items-center justify-between text-base"},B={class:"w-180px"},R=b({__name:"analysis-content-publish",setup(D){const l=a("today"),r=a([]),n=a([]),i=a([]),c=a([]),u=a(),o=x(),{contentPublishRadio:p}=w(),d=p();r.value=d[0].x,d.forEach(e=>{e.name==="纯文本"?n.value=e.y:e.name==="图文类"&&(i.value=e.y),c.value=e.y});function m(e){return e.map(t=>`<div class="content-panel">
      <p>
        <span style="background-color: ${t.color}" class="tooltip-item-icon"></span>
      <span>
        ${t.seriesName}
      </span>
    </p>
    <span class="tooltip-value">
      ${Number(t.value).toLocaleString()}
    </span>
  </div>`).join("")}const v=h(()=>({grid:{left:"4%",right:0,top:"20",bottom:"60"},legend:{bottom:0,icon:"circle",textStyle:{color:"#4E5969"}},xAxis:{type:"category",data:r.value,axisLine:{lineStyle:{color:o.value==="dark"?"#3f3f3f":"#A9AEB8"}},axisTick:{show:!0,alignWithLabel:!0,lineStyle:{color:"#86909C"}},axisLabel:{color:"#86909C"}},yAxis:{type:"value",axisLabel:{color:"#86909C",formatter(e,t){return t===0?`${e}`:`${e/1e3}k`}},splitLine:{lineStyle:{color:o.value==="dark"?"#3F3F3F":"#E5E6EB"}}},tooltip:{show:!0,trigger:"axis",formatter(e){const[t]=e;return`
          <div>
            <p class="tooltip-title">
              ${t.axisValueLabel}
            </p>
            ${m(e)}
          </div>
        `},className:"echarts-tooltip-diy"},series:[{name:"纯文本",data:n.value,stack:"one",type:"bar",barWidth:16,color:o.value==="dark"?"#4A7FF7":"#246EFF"},{name:"图文类",data:i.value,stack:"one",type:"bar",color:o.value==="dark"?"#085FEF":"#00B2FF"},{name:"视频类",data:c.value,stack:"one",type:"bar",color:o.value==="dark"?"#01349F":"#81E2FF",itemStyle:{borderRadius:2}}]}));return A(u).setOption(v.value),(e,t)=>{const f=k("m-tabs");return F(),_("div",L,[s("div",$,[t[1]||(t[1]=s("div",null,"内容发布比例",-1)),s("div",B,[g(f,{modelValue:C(l),"onUpdate:modelValue":t[0]||(t[0]=y=>E(l)?l.value=y:null),class:"h-7 w-full text-sm",options:[{label:"一周内",value:"week"},{label:"昨日",value:"yesterday"},{label:"今日",value:"today"}]},null,8,["modelValue"])])]),s("div",{ref_key:"echarts",ref:u,class:"mt-5 h-300px"},null,512)])}}});export{R as _};
