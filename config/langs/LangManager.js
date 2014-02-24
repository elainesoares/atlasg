
function LangManager(lang)
{
    this.source = lang
}

LangManager.prototype.getString = function (id)
{
    
    return global_pvt_lang_object[id];
};