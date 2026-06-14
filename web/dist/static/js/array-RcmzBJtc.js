const i=(c,s,n=!1)=>{if(!c)return[];const t=(r,e)=>s(r)-s(e),o=(r,e)=>s(e)-s(r);return c.slice().sort(n===!0?o:t)},l=(c,s,n)=>{let t=n;for(let o=1;o<=c;o++)t=s(t,o);return t};export{l as i,i as s};
