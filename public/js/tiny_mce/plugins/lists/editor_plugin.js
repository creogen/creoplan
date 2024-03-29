(function () {
    var e = tinymce.each, r = tinymce.dom.Event, g;

    function p(t, s) {
        while (t && (t.nodeType === 8 || (t.nodeType === 3 && /^[ \t\n\r]*$/.test(t.nodeValue)))) {
            t = s(t)
        }
        return t
    }

    function b(s) {
        return p(s, function (t) {
            return t.previousSibling
        })
    }

    function i(s) {
        return p(s, function (t) {
            return t.nextSibling
        })
    }

    function d(s, u, t) {
        return s.dom.getParent(u, function (v) {
            return tinymce.inArray(t, v) !== -1
        })
    }

    function n(s) {
        return s && (s.tagName === "OL" || s.tagName === "UL")
    }

    function c(u, v) {
        var t, w, s;
        t = b(u.lastChild);
        while (n(t)) {
            w = t;
            t = b(w.previousSibling)
        }
        if (w) {
            s = v.create("li", {style: "list-style-type: none;"});
            v.split(u, w);
            v.insertAfter(s, w);
            s.appendChild(w);
            s.appendChild(w);
            u = s.previousSibling
        }
        return u
    }

    function m(t, s, u) {
        t = a(t, s, u);
        return o(t, s, u)
    }

    function a(u, s, v) {
        var t = b(u.previousSibling);
        if (t) {
            return h(t, u, s ? t : false, v)
        } else {
            return u
        }
    }

    function o(u, t, v) {
        var s = i(u.nextSibling);
        if (s) {
            return h(u, s, t ? s : false, v)
        } else {
            return u
        }
    }

    function h(u, s, t, v) {
        if (l(u, s, !!t, v)) {
            return f(u, s, t)
        } else {
            if (u && u.tagName === "LI" && n(s)) {
                u.appendChild(s)
            }
        }
        return s
    }

    function l(u, t, s, v) {
        if (!u || !t) {
            return false
        } else {
            if (u.tagName === "LI" && t.tagName === "LI") {
                return t.style.listStyleType === "none" || j(t)
            } else {
                if (n(u)) {
                    return(u.tagName === t.tagName && (s || u.style.listStyleType === t.style.listStyleType)) || q(t)
                } else {
                    return v && u.tagName === "P" && t.tagName === "P"
                }
            }
        }
    }

    function q(t) {
        var s = i(t.firstChild), u = b(t.lastChild);
        return s && u && n(t) && s === u && (n(s) || s.style.listStyleType === "none" || j(s))
    }

    function j(u) {
        var t = i(u.firstChild), s = b(u.lastChild);
        return t && s && t === s && n(t)
    }

    function f(w, v, s) {
        var u = b(w.lastChild), t = i(v.firstChild);
        if (w.tagName === "P") {
            w.appendChild(w.ownerDocument.createElement("br"))
        }
        while (v.firstChild) {
            w.appendChild(v.firstChild)
        }
        if (s) {
            w.style.listStyleType = s.style.listStyleType
        }
        v.parentNode.removeChild(v);
        h(u, t, false);
        return w
    }

    function k(t, u) {
        var s;
        if (!u.is(t, "li,ol,ul")) {
            s = u.getParent(t, "li");
            if (s) {
                t = s
            }
        }
        return t
    }

    tinymce.create("tinymce.plugins.Lists", {init: function (y) {
        var v = "TABBING";
        var s = "EMPTY";
        var I = "ESCAPE";
        var z = "PARAGRAPH";
        var M = "UNKNOWN";
        var x = M;

        function E(T) {
            return T.keyCode === tinymce.VK.TAB && !(T.altKey || T.ctrlKey) && (y.queryCommandState("InsertUnorderedList") || y.queryCommandState("InsertOrderedList"))
        }

        function D() {
            var T = y.selection.getRng();
            var U = T.startContainer;
            if (U.nodeType == 3) {
                return(T.endOffset == U.nodeValue.length)
            } else {
                if (U.nodeType == 1) {
                    return T.endOffset == U.childNodes.length
                }
            }
            return false
        }

        function N() {
            var U = y.selection.getNode();
            var T = U.tagName === "P" && U.parentNode.tagName === "LI" && U.parentNode.lastChild === U;
            return y.selection.isCollapsed() && T && D()
        }

        function w() {
            var T = B();
            var V = T.parentNode.parentNode;
            var U = T.parentNode.lastChild === T;
            return U && !t(V) && O(T)
        }

        function t(T) {
            if (n(T)) {
                return T.parentNode && T.parentNode.tagName === "LI"
            } else {
                return T.tagName === "LI"
            }
        }

        function F() {
            return y.selection.isCollapsed() && O(B())
        }

        function B() {
            var T = y.selection.getStart();
            return((T.tagName == "BR" || T.tagName == "") && T.parentNode.tagName == "LI") ? T.parentNode : T
        }

        function O(T) {
            var U = T.childNodes.length;
            if (T.tagName === "LI") {
                return U == 0 ? true : U == 1 && (T.firstChild.tagName == "" || T.firstChild.tagName == "BR" || H(T))
            }
            return false
        }

        function H(T) {
            var U = tinymce.grep(T.parentNode.childNodes, function (X) {
                return X.tagName == "LI"
            });
            var V = T == U[U.length - 1];
            var W = T.firstChild;
            return tinymce.isIE9 && V && (W.nodeValue == String.fromCharCode(160) || W.nodeValue == String.fromCharCode(32))
        }

        function S(T) {
            return T.keyCode === tinymce.VK.ENTER
        }

        function A(T) {
            return S(T) && !T.shiftKey
        }

        function L(T) {
            if (E(T)) {
                return v
            } else {
                if (A(T) && w()) {
                    return I
                } else {
                    if (A(T) && F()) {
                        return s
                    } else {
                        if (A(T) && N()) {
                            return z
                        } else {
                            return M
                        }
                    }
                }
            }
        }

        function C(T, U) {
            if (x == v || x == s || tinymce.isGecko && x == I) {
                r.cancel(U)
            }
        }

        function J(V, X) {
            if (x == z) {
                var W = V.selection.getNode();
                var U = V.dom.create("li");
                var T = V.dom.getParent(W, "li");
                V.dom.insertAfter(U, T);
                if (tinyMCE.isIE8) {
                    U.appendChild(V.dom.create("&nbsp;"));
                    V.selection.setCursorLocation(U, 1)
                } else {
                    if (tinyMCE.isGecko) {
                        setTimeout(function () {
                            var Y = V.getDoc().createTextNode("\uFEFF");
                            U.appendChild(Y);
                            V.selection.setCursorLocation(U, 0)
                        }, 0)
                    } else {
                        V.selection.setCursorLocation(U, 0)
                    }
                }
                r.cancel(X)
            }
        }

        function u(W, Y) {
            var ab;
            if (!tinymce.isGecko) {
                return
            }
            var U = W.selection.getStart();
            if (Y.keyCode != tinymce.VK.BACKSPACE || U.tagName !== "IMG") {
                return
            }
            function V(af) {
                var ag = af.firstChild;
                var ae = null;
                do {
                    if (!ag) {
                        break
                    }
                    if (ag.tagName === "LI") {
                        ae = ag
                    }
                } while (ag = ag.nextSibling);
                return ae
            }

            function ad(af, ae) {
                while (af.childNodes.length > 0) {
                    ae.appendChild(af.childNodes[0])
                }
            }

            ab = U.parentNode.previousSibling;
            if (!ab) {
                return
            }
            var Z;
            if (ab.tagName === "UL" || ab.tagName === "OL") {
                Z = ab
            } else {
                if (ab.previousSibling && (ab.previousSibling.tagName === "UL" || ab.previousSibling.tagName === "OL")) {
                    Z = ab.previousSibling
                } else {
                    return
                }
            }
            var ac = V(Z);
            var T = W.dom.createRng();
            T.setStart(ac, 1);
            T.setEnd(ac, 1);
            W.selection.setRng(T);
            W.selection.collapse(true);
            var X = W.selection.getBookmark();
            var aa = U.parentNode.cloneNode(true);
            if (aa.tagName === "P" || aa.tagName === "DIV") {
                ad(aa, ac)
            } else {
                ac.appendChild(aa)
            }
            U.parentNode.parentNode.removeChild(U.parentNode);
            W.selection.moveToBookmark(X)
        }

        function G(T) {
            var U = y.dom.getParent(T, "ol,ul");
            if (U != null) {
                var V = U.lastChild;
                V.appendChild(y.getDoc().createElement(""));
                y.selection.setCursorLocation(V, 0)
            }
        }

        this.ed = y;
        y.addCommand("Indent", this.indent, this);
        y.addCommand("Outdent", this.outdent, this);
        y.addCommand("InsertUnorderedList", function () {
            this.applyList("UL", "OL")
        }, this);
        y.addCommand("InsertOrderedList", function () {
            this.applyList("OL", "UL")
        }, this);
        y.onInit.add(function () {
            y.editorCommands.addCommands({outdent: function () {
                var U = y.selection, V = y.dom;

                function T(W) {
                    W = V.getParent(W, V.isBlock);
                    return W && (parseInt(y.dom.getStyle(W, "margin-left") || 0, 10) + parseInt(y.dom.getStyle(W, "padding-left") || 0, 10)) > 0
                }

                return T(U.getStart()) || T(U.getEnd()) || y.queryCommandState("InsertOrderedList") || y.queryCommandState("InsertUnorderedList")
            }}, "state")
        });
        y.onKeyUp.add(function (U, V) {
            if (x == v) {
                U.execCommand(V.shiftKey ? "Outdent" : "Indent", true, null);
                x = M;
                return r.cancel(V)
            } else {
                if (x == s) {
                    var T = B();
                    var X = U.settings.list_outdent_on_enter === true || V.shiftKey;
                    U.execCommand(X ? "Outdent" : "Indent", true, null);
                    if (tinymce.isIE) {
                        G(T)
                    }
                    return r.cancel(V)
                } else {
                    if (x == I) {
                        if (tinymce.isIE8) {
                            var W = U.getDoc().createTextNode("\uFEFF");
                            U.selection.getNode().appendChild(W)
                        } else {
                            if (tinymce.isIE9 || tinymce.isGecko) {
                                U.execCommand("Outdent");
                                return r.cancel(V)
                            }
                        }
                    }
                }
            }
        });
        function K(U, T) {
            var V = y.getDoc().createTextNode("\uFEFF");
            U.insertBefore(V, T);
            y.selection.setCursorLocation(V, 0);
            y.execCommand("mceRepaint")
        }

        function Q(U, W) {
            if (S(W)) {
                var T = B();
                if (T) {
                    var V = T.parentNode;
                    var X = V && V.parentNode;
                    if (X && X.nodeName == "LI" && X.firstChild == V && T == V.firstChild) {
                        K(X, V)
                    }
                }
            }
        }

        function R(U, W) {
            if (S(W)) {
                var T = B();
                if (U.dom.select("ul li", T).length === 1) {
                    var V = T.firstChild;
                    K(T, V)
                }
            }
        }

        function P(U, Y) {
            function V(ac, Z) {
                var ab = [];
                var ad = new tinymce.dom.TreeWalker(Z, ac);
                for (var aa = ad.current(); aa; aa = ad.next()) {
                    if (U.dom.is(aa, "ol,ul,li")) {
                        ab.push(aa)
                    }
                }
                return ab
            }

            if (Y.keyCode == tinymce.VK.BACKSPACE) {
                var T = B();
                if (T) {
                    var X = U.dom.getParent(T, "ol,ul");
                    if (X && X.firstChild === T) {
                        var W = V(X, T);
                        U.execCommand("Outdent", false, W);
                        U.undoManager.add();
                        return r.cancel(Y)
                    }
                }
            }
        }

        y.onKeyDown.add(function (T, U) {
            x = L(U)
        });
        y.onKeyDown.add(C);
        y.onKeyDown.add(u);
        y.onKeyDown.add(J);
        if (tinymce.isGecko) {
            y.onKeyUp.add(Q)
        }
        if (tinymce.isIE8) {
            y.onKeyUp.add(R)
        }
        if (tinymce.isGecko || tinymce.isWebKit) {
            y.onKeyDown.add(P)
        }
    }, applyList: function (y, v) {
        var C = this, z = C.ed, I = z.dom, s = [], H = false, u = false, w = false, B, G = z.selection.getSelectedBlocks();

        function E(t) {
            if (t && t.tagName === "BR") {
                I.remove(t)
            }
        }

        function F(M) {
            var N = I.create(y), t;

            function L(O) {
                if (O.style.marginLeft || O.style.paddingLeft) {
                    C.adjustPaddingFunction(false)(O)
                }
            }

            if (M.tagName === "LI") {
            } else {
                if (M.tagName === "P" || M.tagName === "DIV" || M.tagName === "BODY") {
                    K(M, function (P, O) {
                        J(P, O, M.tagName === "BODY" ? null : P.parentNode);
                        t = P.parentNode;
                        L(t);
                        E(O)
                    });
                    if (t) {
                        if (t.tagName === "LI" && (M.tagName === "P" || G.length > 1)) {
                            I.split(t.parentNode.parentNode, t.parentNode)
                        }
                        m(t.parentNode, true)
                    }
                    return
                } else {
                    t = I.create("li");
                    I.insertAfter(t, M);
                    t.appendChild(M);
                    L(M);
                    M = t
                }
            }
            I.insertAfter(N, M);
            N.appendChild(M);
            m(N, true);
            s.push(M)
        }

        function J(P, L, N) {
            var t, O = P, M;
            while (!I.isBlock(P.parentNode) && P.parentNode !== I.getRoot()) {
                P = I.split(P.parentNode, P.previousSibling);
                P = P.nextSibling;
                O = P
            }
            if (N) {
                t = N.cloneNode(true);
                P.parentNode.insertBefore(t, P);
                while (t.firstChild) {
                    I.remove(t.firstChild)
                }
                t = I.rename(t, "li")
            } else {
                t = I.create("li");
                P.parentNode.insertBefore(t, P)
            }
            while (O && O != L) {
                M = O.nextSibling;
                t.appendChild(O);
                O = M
            }
            if (t.childNodes.length === 0) {
                t.innerHTML = '<br _mce_bogus="1" />'
            }
            F(t)
        }

        function K(Q, T) {
            var N, R, O = 3, L = 1, t = "br,ul,ol,p,div,h1,h2,h3,h4,h5,h6,table,blockquote,address,pre,form,center,dl";

            function P(X, U) {
                var V = I.createRng(), W;
                g.keep = true;
                z.selection.moveToBookmark(g);
                g.keep = false;
                W = z.selection.getRng(true);
                if (!U) {
                    U = X.parentNode.lastChild
                }
                V.setStartBefore(X);
                V.setEndAfter(U);
                return !(V.compareBoundaryPoints(O, W) > 0 || V.compareBoundaryPoints(L, W) <= 0)
            }

            function S(U) {
                if (U.nextSibling) {
                    return U.nextSibling
                }
                if (!I.isBlock(U.parentNode) && U.parentNode !== I.getRoot()) {
                    return S(U.parentNode)
                }
            }

            N = Q.firstChild;
            var M = false;
            e(I.select(t, Q), function (U) {
                if (U.hasAttribute && U.hasAttribute("_mce_bogus")) {
                    return true
                }
                if (P(N, U)) {
                    I.addClass(U, "_mce_tagged_br");
                    N = S(U)
                }
            });
            M = (N && P(N, undefined));
            N = Q.firstChild;
            e(I.select(t, Q), function (V) {
                var U = S(V);
                if (V.hasAttribute && V.hasAttribute("_mce_bogus")) {
                    return true
                }
                if (I.hasClass(V, "_mce_tagged_br")) {
                    T(N, V, R);
                    R = null
                } else {
                    R = V
                }
                N = U
            });
            if (M) {
                T(N, undefined, R)
            }
        }

        function D(t) {
            K(t, function (M, L, N) {
                J(M, L);
                E(L);
                E(N)
            })
        }

        function A(t) {
            if (tinymce.inArray(s, t) !== -1) {
                return
            }
            if (t.parentNode.tagName === v) {
                I.split(t.parentNode, t);
                F(t);
                o(t.parentNode, false)
            }
            s.push(t)
        }

        function x(M) {
            var O, N, L, t;
            if (tinymce.inArray(s, M) !== -1) {
                return
            }
            M = c(M, I);
            while (I.is(M.parentNode, "ol,ul,li")) {
                I.split(M.parentNode, M)
            }
            s.push(M);
            M = I.rename(M, "p");
            L = m(M, false, z.settings.force_br_newlines);
            if (L === M) {
                O = M.firstChild;
                while (O) {
                    if (I.isBlock(O)) {
                        O = I.split(O.parentNode, O);
                        t = true;
                        N = O.nextSibling && O.nextSibling.firstChild
                    } else {
                        N = O.nextSibling;
                        if (t && O.tagName === "BR") {
                            I.remove(O)
                        }
                        t = false
                    }
                    O = N
                }
            }
        }

        e(G, function (t) {
            t = k(t, I);
            if (t.tagName === v || (t.tagName === "LI" && t.parentNode.tagName === v)) {
                u = true
            } else {
                if (t.tagName === y || (t.tagName === "LI" && t.parentNode.tagName === y)) {
                    H = true
                } else {
                    w = true
                }
            }
        });
        if (w && !H || u || G.length === 0) {
            B = {LI: A, H1: F, H2: F, H3: F, H4: F, H5: F, H6: F, P: F, BODY: F, DIV: G.length > 1 ? F : D, defaultAction: D, elements: this.selectedBlocks()}
        } else {
            B = {defaultAction: x, elements: this.selectedBlocks()}
        }
        this.process(B)
    }, indent: function () {
        var u = this.ed, w = u.dom, x = [];

        function s(z) {
            var y = w.create("li", {style: "list-style-type: none;"});
            w.insertAfter(y, z);
            return y
        }

        function t(B) {
            var y = s(B), D = w.getParent(B, "ol,ul"), C = D.tagName, E = w.getStyle(D, "list-style-type"), A = {}, z;
            if (E !== "") {
                A.style = "list-style-type: " + E + ";"
            }
            z = w.create(C, A);
            y.appendChild(z);
            return z
        }

        function v(z) {
            if (!d(u, z, x)) {
                z = c(z, w);
                var y = t(z);
                y.appendChild(z);
                m(y.parentNode, false);
                m(y, false);
                x.push(z)
            }
        }

        this.process({LI: v, defaultAction: this.adjustPaddingFunction(true), elements: this.selectedBlocks()})
    }, outdent: function (y, x) {
        var w = this, u = w.ed, z = u.dom, s = [];

        function A(t) {
            var C, B, D;
            if (!d(u, t, s)) {
                if (z.getStyle(t, "margin-left") !== "" || z.getStyle(t, "padding-left") !== "") {
                    return w.adjustPaddingFunction(false)(t)
                }
                D = z.getStyle(t, "text-align", true);
                if (D === "center" || D === "right") {
                    z.setStyle(t, "text-align", "left");
                    return
                }
                t = c(t, z);
                C = t.parentNode;
                B = t.parentNode.parentNode;
                if (B.tagName === "P") {
                    z.split(B, t.parentNode)
                } else {
                    z.split(C, t);
                    if (B.tagName === "LI") {
                        z.split(B, t)
                    } else {
                        if (!z.is(B, "ol,ul")) {
                            z.rename(t, "p")
                        }
                    }
                }
                s.push(t)
            }
        }

        var v = x && tinymce.is(x, "array") ? x : this.selectedBlocks();
        this.process({LI: A, defaultAction: this.adjustPaddingFunction(false), elements: v});
        e(s, m)
    }, process: function (y) {
        var F = this, w = F.ed.selection, z = F.ed.dom, E, u;

        function B(t) {
            var s = tinymce.grep(t.childNodes, function (H) {
                return !(H.nodeName === "BR" || H.nodeName === "SPAN" && z.getAttrib(H, "data-mce-type") == "bookmark" || H.nodeType == 3 && (H.nodeValue == String.fromCharCode(160) || H.nodeValue == ""))
            });
            return s.length === 0
        }

        function x(s) {
            z.removeClass(s, "_mce_act_on");
            if (!s || s.nodeType !== 1 || E.length > 1 && B(s)) {
                return
            }
            s = k(s, z);
            var t = y[s.tagName];
            if (!t) {
                t = y.defaultAction
            }
            t(s)
        }

        function v(s) {
            F.splitSafeEach(s.childNodes, x)
        }

        function C(s, t) {
            return t >= 0 && s.hasChildNodes() && t < s.childNodes.length && s.childNodes[t].tagName === "BR"
        }

        function D() {
            var t = w.getNode();
            var s = z.getParent(t, "td");
            return s !== null
        }

        E = y.elements;
        u = w.getRng(true);
        if (!u.collapsed) {
            if (C(u.endContainer, u.endOffset - 1)) {
                u.setEnd(u.endContainer, u.endOffset - 1);
                w.setRng(u)
            }
            if (C(u.startContainer, u.startOffset)) {
                u.setStart(u.startContainer, u.startOffset + 1);
                w.setRng(u)
            }
        }
        if (tinymce.isIE8) {
            var G = F.ed.selection.getNode();
            if (G.tagName === "LI" && !(G.parentNode.lastChild === G)) {
                var A = F.ed.getDoc().createTextNode("\uFEFF");
                G.appendChild(A)
            }
        }
        g = w.getBookmark();
        y.OL = y.UL = v;
        F.splitSafeEach(E, x);
        w.moveToBookmark(g);
        g = null;
        if (!D()) {
            F.ed.execCommand("mceRepaint")
        }
    }, splitSafeEach: function (t, s) {
        if (tinymce.isGecko && (/Firefox\/[12]\.[0-9]/.test(navigator.userAgent) || /Firefox\/3\.[0-4]/.test(navigator.userAgent))) {
            this.classBasedEach(t, s)
        } else {
            e(t, s)
        }
    }, classBasedEach: function (v, u) {
        var w = this.ed.dom, s, t;
        e(v, function (x) {
            w.addClass(x, "_mce_act_on")
        });
        s = w.select("._mce_act_on");
        while (s.length > 0) {
            t = s.shift();
            w.removeClass(t, "_mce_act_on");
            u(t);
            s = w.select("._mce_act_on")
        }
    }, adjustPaddingFunction: function (u) {
        var s, v, t = this.ed;
        s = t.settings.indentation;
        v = /[a-z%]+/i.exec(s);
        s = parseInt(s, 10);
        return function (w) {
            var y, x;
            y = parseInt(t.dom.getStyle(w, "margin-left") || 0, 10) + parseInt(t.dom.getStyle(w, "padding-left") || 0, 10);
            if (u) {
                x = y + s
            } else {
                x = y - s
            }
            t.dom.setStyle(w, "padding-left", "");
            t.dom.setStyle(w, "margin-left", x > 0 ? x + v : "")
        }
    }, selectedBlocks: function () {
        var s = this.ed;
        var t = s.selection.getSelectedBlocks();
        return t.length == 0 ? [s.dom.getRoot()] : t
    }, getInfo: function () {
        return{longname: "Lists", author: "Moxiecode Systems AB", authorurl: "http://tinymce.moxiecode.com", infourl: "http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/lists", version: tinymce.majorVersion + "." + tinymce.minorVersion}
    }});
    tinymce.PluginManager.add("lists", tinymce.plugins.Lists)
}());