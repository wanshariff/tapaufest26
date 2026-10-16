#!/usr/bin/env python3
"""
Build the TAPAUfest icon sprite.

Icons are drawn on a 24x24 grid with a 2px round stroke in currentColor, then
"roughened": every line, circle and arc is redrawn with a small seeded wobble
so the set feels hand-drawn like the illustrations, while staying crisp and
tiny (no SVG filters, which blur at small sizes and cost render time).

Accent areas (class tf-i-a) are filled with currentColor at 18% opacity,
echoing the flat fills in the illustrations.

Output: wp-content/plugins/tapaufest-core/assets/icons/sprite.svg and
icons.json (the list of names). Run: python3 tools/build-icons.py
"""
import json
import math
import os
import random

from svgelements import Path, Close, Move  # pip install svgelements

# Hand-drawn pass settings (px on the 24 grid).
WOBBLE = 0.42      # how far a line drifts from the true path
WAVE = (7.0, 13.0) # wavelength range of the drift, so it reads as a slow hand, not jitter
OVERSHOOT = 0.7    # open strokes run a little past their end
OVERLAP = 1.6      # closed shapes are not closed: the pen runs past the start
WIDTH = (1.75, 2.3)  # pen pressure: each stroke gets its own width

OUT = os.path.join(os.path.dirname(__file__), '..', 'wp-content', 'plugins', 'tapaufest-core', 'assets', 'icons')
J = 0.32  # wobble in px


def f(v):
    return ('%.2f' % v).rstrip('0').rstrip('.')


def _noise(rng, scale=1.0):
    """Smooth 2D drift along arc length: two slow sine waves per axis."""
    waves = []
    for _ in range(2):
        waves.append((rng.uniform(*WAVE), rng.uniform(0, 6.283), rng.uniform(*WAVE), rng.uniform(0, 6.283)))
    amp = WOBBLE * scale

    def at(t):
        dx = sum(math.sin(6.283 * t / a + b) for a, b, _, _ in waves) * amp / 2
        dy = sum(math.sin(6.283 * t / c + e) for _, _, c, e in waves) * amp / 2
        return dx, dy
    return at


def _rdp(pts, eps):
    if len(pts) < 3:
        return pts
    (x1, y1), (x2, y2) = pts[0], pts[-1]
    L = math.hypot(x2 - x1, y2 - y1) or 1e-9
    best, idx = 0, 0
    for i in range(1, len(pts) - 1):
        x0, y0 = pts[i]
        dd = abs((x2 - x1) * (y1 - y0) - (x1 - x0) * (y2 - y1)) / L
        if dd > best:
            best, idx = dd, i
    if best > eps:
        return _rdp(pts[:idx + 1], eps)[:-1] + _rdp(pts[idx:], eps)
    return [pts[0], pts[-1]]


def _g(v):
    return ('%.1f' % v).rstrip('0').rstrip('.').replace('-0', '-0') if abs(v) >= 0.05 else '0'


def _curve(pts):
    """Catmull-Rom through the points, as cubic Beziers."""
    d = 'M%s %s' % (_g(pts[0][0]), _g(pts[0][1]))
    if len(pts) == 2:
        return d + 'L%s %s' % (_g(pts[1][0]), _g(pts[1][1]))
    for i in range(len(pts) - 1):
        p0 = pts[i - 1] if i > 0 else pts[i]
        p1, p2 = pts[i], pts[i + 1]
        p3 = pts[i + 2] if i + 2 < len(pts) else p2
        c1 = (p1[0] + (p2[0] - p0[0]) / 6, p1[1] + (p2[1] - p0[1]) / 6)
        c2 = (p2[0] - (p3[0] - p1[0]) / 6, p2[1] - (p3[1] - p1[1]) / 6)
        d += 'C%s %s %s %s %s %s' % (_g(c1[0]), _g(c1[1]), _g(c2[0]), _g(c2[1]), _g(p2[0]), _g(p2[1]))
    return d


def sketch(d, rng):
    """Redraw any path as if by hand: slow drift, overshoot, unclosed loops."""
    out = []
    for sub in Path(d).as_subpaths():
        segs = [sg for sg in sub.segments() if not isinstance(sg, Move)]
        if not segs:
            continue
        closed = any(isinstance(sg, Close) for sg in segs)
        pts, corners = [], set()
        for sg in segs:
            if sg.start is None or sg.end is None:
                continue
            L = sg.length(error=1e-3) or 0
            if L < 1e-6:
                continue
            k = max(1, int(round(L / 2.2)))
            if not pts:
                pts.append((sg.start.x, sg.start.y))
            for j in range(1, k + 1):
                q = sg.point(j / k)
                pts.append((q.x, q.y))
            corners.add(len(pts) - 1)
        if len(pts) < 2:
            continue
        if closed and math.hypot(pts[0][0] - pts[-1][0], pts[0][1] - pts[-1][1]) < 0.01:
            pts.pop()
        # arc length per point
        acc, run = [0.0], 0.0
        for a, b in zip(pts, pts[1:]):
            run += math.hypot(b[0] - a[0], b[1] - a[1])
            acc.append(run)
        # small shapes wobble less, or a 4px circle turns into a blob
        size = min(1.0, max(0.25, run / 30.0))
        nz = _noise(rng, size)
        moved = [(x + nz(t)[0], y + nz(t)[1]) for (x, y), t in zip(pts, acc)]
        if closed:
            # carry on past the start, drifting off a little
            extra, t, i = [], run, 0
            seq = pts + pts
            while t < run + OVERLAP * size + math.hypot(pts[0][0] - pts[-1][0], pts[0][1] - pts[-1][1]) and i < len(pts):
                a = seq[len(pts) - 1 + i] if i else pts[-1]
                b = pts[i]
                t += math.hypot(b[0] - a[0], b[1] - a[1])
                dx, dy = nz(t)
                drift = 0.35 * size * (t - run) / max(OVERLAP, 0.1)
                extra.append((b[0] + dx + drift * 0.6, b[1] + dy + drift * 0.4))
                i += 1
            moved += extra
        else:
            for end, nxt in ((0, 1), (len(moved) - 1, len(moved) - 2)):
                (x, y), (nx_, ny_) = moved[end], moved[nxt]
                L = math.hypot(x - nx_, y - ny_) or 1
                o = OVERSHOOT * rng.uniform(0.3, 1.0) * max(0.4, size)
                moved[end] = (x + (x - nx_) / L * o, y + (y - ny_) / L * o)
        keep = _rdp(moved, 0.14)
        out.append(_curve(keep))
    return ''.join(out)


class Icon:
    def __init__(self, name, seed):
        self.name = name
        self.rng = random.Random(seed)
        self.parts = []

    def j(self, amt=J):
        return self.rng.uniform(-amt, amt)

    # --- primitives -------------------------------------------------------
    def line(self, x1, y1, x2, y2, acc=False):
        mx, my = (x1 + x2) / 2, (y1 + y2) / 2
        L = math.hypot(x2 - x1, y2 - y1) or 1
        nx, ny = -(y2 - y1) / L, (x2 - x1) / L
        b = self.j() * min(1.0, L / 8)
        d = 'M%s %s Q%s %s %s %s' % (f(x1 + self.j(0.12)), f(y1 + self.j(0.12)), f(mx + nx * b), f(my + ny * b), f(x2 + self.j(0.12)), f(y2 + self.j(0.12)))
        self.parts.append(('p', d, acc))
        return self

    def poly(self, pts, close=False, acc=False):
        d = ''
        for i, (x, y) in enumerate(pts):
            x += self.j(0.15)
            y += self.j(0.15)
            if i == 0:
                d += 'M%s %s' % (f(x), f(y))
            else:
                px, py = pts[i - 1]
                mx, my = (px + x) / 2 + self.j(0.25), (py + y) / 2 + self.j(0.25)
                d += ' Q%s %s %s %s' % (f(mx), f(my), f(x), f(y))
        if close:
            x0, y0 = pts[0]
            px, py = pts[-1]
            d += ' Q%s %s %s %s Z' % (f((px + x0) / 2 + self.j(0.25)), f((py + y0) / 2 + self.j(0.25)), f(x0), f(y0))
        self.parts.append(('p', d, acc))
        return self

    def circle(self, cx, cy, r, acc=False):
        k = 0.5523
        pts = []
        for a in (0, 90, 180, 270):
            rr = r * (1 + self.j(0.04))
            pts.append((cx + rr * math.cos(math.radians(a)), cy + rr * math.sin(math.radians(a))))
        d = 'M%s %s' % (f(pts[0][0]), f(pts[0][1]))
        for i in range(4):
            a0 = math.radians(i * 90)
            a1 = math.radians((i + 1) * 90)
            p0 = pts[i]
            p1 = pts[(i + 1) % 4]
            r0 = math.hypot(p0[0] - cx, p0[1] - cy)
            r1 = math.hypot(p1[0] - cx, p1[1] - cy)
            c1 = (p0[0] - r0 * k * math.sin(a0), p0[1] + r0 * k * math.cos(a0))
            c2 = (p1[0] + r1 * k * math.sin(a1), p1[1] - r1 * k * math.cos(a1))
            d += ' C%s %s %s %s %s %s' % (f(c1[0]), f(c1[1]), f(c2[0]), f(c2[1]), f(p1[0]), f(p1[1]))
        d += 'Z'
        self.parts.append(('p', d, acc))
        return self

    def rrect(self, x, y, w, h, r, acc=False):
        r = min(r, w / 2, h / 2)
        jj = lambda: self.j(0.18)
        d = ('M%s %s H%s Q%s %s %s %s V%s Q%s %s %s %s H%s Q%s %s %s %s V%s Q%s %s %s %s Z' % (
            f(x + r + jj()), f(y + jj()), f(x + w - r + jj()), f(x + w), f(y), f(x + w + jj()), f(y + r),
            f(y + h - r + jj()), f(x + w), f(y + h), f(x + w - r), f(y + h + jj()),
            f(x + r + jj()), f(x), f(y + h), f(x + jj()), f(y + h - r),
            f(y + r + jj()), f(x), f(y), f(x + r), f(y)))
        self.parts.append(('p', d, acc))
        return self

    def path(self, d, acc=False):
        self.parts.append(('p', d, acc))
        return self

    def dot(self, cx, cy, r=1.1):
        self.parts.append(('dot', (cx, cy, r), False))
        return self

    def svg(self):
        out = []
        for kind, d, acc in self.parts:
            if kind == 'dot':
                cx, cy, r = d
                out.append('<circle cx="%s" cy="%s" r="%s" fill="currentColor" stroke="none"/>' % (f(cx), f(cy), f(r)))
            elif acc == 'solid':
                out.append('<path d="%s" fill="currentColor"/><path d="%s" stroke-width="1.6"/>' % (d, sketch(d, self.rng)))
            elif acc:
                # misregistered fill, like a second print colour slightly off the line
                out.append('<path class="tf-i-a" d="%s" fill="currentColor" fill-opacity=".2" stroke="none" transform="translate(.9 1)"/>' % d)
            else:
                w = self.rng.uniform(*WIDTH)
                out.append('<path d="%s" stroke-width="%s"/>' % (sketch(d, self.rng), f(round(w, 2))))
        return ('<symbol id="tf-i-%s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" '
                'stroke-linecap="round" stroke-linejoin="round">%s</symbol>') % (self.name, ''.join(out))


ICONS = []


def icon(name):
    def deco(fn):
        ic = Icon(name, sum(ord(c) for c in name))
        fn(ic)
        ICONS.append(ic)
        return fn
    return deco


# ---------------------------------------------------------------- interface
@icon('arrow-right')
def _(i): i.line(4, 12, 19.5, 12).poly([(13.5, 6), (19.5, 12), (13.5, 18)])

@icon('arrow-left')
def _(i): i.line(20, 12, 4.5, 12).poly([(10.5, 6), (4.5, 12), (10.5, 18)])

@icon('external')
def _(i): i.line(6, 18, 18, 6).poly([(9, 6), (18, 6), (18, 15)])

@icon('chevron-down')
def _(i): i.poly([(6, 9), (12, 15), (18, 9)])

@icon('close')
def _(i): i.line(6, 6, 18, 18).line(18, 6, 6, 18)

@icon('menu')
def _(i): i.line(4, 7, 20, 7).line(4, 12, 20, 12).line(4, 17, 20, 17)

@icon('plus')
def _(i): i.line(12, 5, 12, 19).line(5, 12, 19, 12)

@icon('minus')
def _(i): i.line(5, 12, 19, 12)

@icon('check')
def _(i): i.poly([(5, 12.5), (10, 17.5), (19.5, 6.5)])

@icon('play')
def _(i): i.poly([(8, 5), (19, 12), (8, 19)], close=True, acc=True).poly([(8, 5), (19, 12), (8, 19)], close=True)

@icon('star')
def _(i):
    pts = []
    for k in range(10):
        a = math.radians(-90 + k * 36)
        r = 9 if k % 2 == 0 else 4
        pts.append((12 + r * math.cos(a), 12.6 + r * math.sin(a)))
    i.poly(pts, close=True)

@icon('star-filled')
def _(i):
    pts = []
    for k in range(10):
        a = math.radians(-90 + k * 36)
        r = 9 if k % 2 == 0 else 4
        pts.append((12 + r * math.cos(a), 12.6 + r * math.sin(a)))
    i.poly(pts, close=True, acc='solid')

@icon('calendar')
def _(i): i.rrect(3.5, 5, 17, 15, 2.5).line(3.5, 10, 20.5, 10).line(8, 3, 8, 7).line(16, 3, 16, 7).rrect(7, 13, 4, 3.5, 0.8, acc=True)

@icon('clock')
def _(i): i.circle(12, 12, 8.5).poly([(12, 7), (12, 12), (15.5, 14)])

@icon('pin')
def _(i): i.path('M12 21.2C12 21.2 5 14.6 5 9.6a7 7 0 0 1 14 0c0 5-7 11.6-7 11.6Z', acc=True).path('M12 21.2C11.9 21.1 5 14.6 5.1 9.5a6.95 7 0 0 1 13.9.1c0 5-7.1 11.6-7 11.6Z').circle(12, 9.6, 2.6)

@icon('share')
def _(i): i.circle(6, 12, 2.6).circle(17.5, 6, 2.6).circle(17.5, 18, 2.6).line(8.3, 10.8, 15.2, 7.2).line(8.3, 13.2, 15.2, 16.8)

@icon('download')
def _(i): i.line(12, 4, 12, 15).poly([(7, 10.5), (12, 15.5), (17, 10.5)]).line(5, 19.5, 19, 19.5)

@icon('info')
def _(i): i.circle(12, 12, 9, acc=True).circle(12, 12, 9).line(12, 11, 12, 16.5).dot(12, 7.6)

@icon('alert')
def _(i): i.poly([(12, 3.5), (21, 19.5), (3, 19.5)], close=True, acc=True).poly([(12, 3.5), (21, 19.5), (3, 19.5)], close=True).line(12, 9.5, 12, 14).dot(12, 16.8)

@icon('search')
def _(i): i.circle(10.5, 10.5, 6.5).line(15.4, 15.4, 20, 20)

# ---------------------------------------------------------------- festival
@icon('ticket')
def _(i): i.path('M4 6.5h16v3.2a2.3 2.3 0 0 0 0 4.6v3.2H4v-3.2a2.3 2.3 0 0 0 0-4.6Z', acc=True).path('M4.1 6.6h15.8v3.1a2.3 2.3 0 0 0 0 4.6v3.2H4v-3.2a2.3 2.2 0 0 0 .1-4.6Z').line(14.5, 7.5, 14.5, 9).line(14.5, 11.2, 14.5, 12.8).line(14.5, 15, 14.5, 16.5)

@icon('stage')
def _(i): i.line(3, 4.5, 21, 4.5).line(5, 4.5, 5, 15).line(19, 4.5, 19, 15).rrect(2.5, 15, 19, 5, 1, acc=True).rrect(2.5, 15, 19, 5, 1).path('M10.5 12.4V7.6l4-.9v4.6').circle(9.4, 12.4, 1.2, acc=True).circle(13.4, 11.3, 1.2, acc=True)

@icon('music')
def _(i): i.path('M9 18V6l10-2v12').circle(6.6, 18, 2.6, acc=True).circle(6.6, 18, 2.6).circle(16.6, 16, 2.6, acc=True).circle(16.6, 16, 2.6)

@icon('film')
def _(i): i.rrect(3, 6, 13, 12, 2, acc=True).rrect(3, 6, 13, 12, 2).poly([(16, 10.5), (21, 7.5), (21, 16.5), (16, 13.5)])

@icon('workshop')
def _(i): i.path('M4 20l3.6-1 9.8-9.8-2.6-2.6L5 16.4Z', acc=True).path('M4 20l3.6-1 9.8-9.8-2.6-2.6L5 16.4Z').line(13.4, 8, 16, 10.6).path('M16.2 5.2l1.3-1.3a1.8 1.8 0 0 1 2.6 2.6l-1.3 1.3')

@icon('food')
def _(i): i.path('M3.5 11h17a8.5 8.5 0 0 1-17 0Z', acc=True).path('M3.5 11.1h17a8.5 8.4 0 0 1-17 0Z').line(9, 19, 15, 19).path('M9 4c-1 1.5 1 2.5 0 4M13 3.5c-1 1.5 1 2.5 0 4').line(16, 3.5, 21, 8.5)

@icon('drink')
def _(i): i.path('M5 8h11v7a5 5 0 0 1-5 5H10a5 5 0 0 1-5-5Z', acc=True).path('M5 8.1h11V15a5 5 0 0 1-5 5h-1a5 5 0 0 1-5-5Z').path('M16 10h1.5a2.5 2.5 0 0 1 0 5H16').path('M8 3.5c-.8 1.2.8 2 0 3M12 3.5c-.8 1.2.8 2 0 3')

@icon('market')
def _(i): i.path('M5 8h14l-1.2 12H6.2Z', acc=True).path('M5 8.1h14L17.8 20H6.2Z').path('M8.8 10.5V7a3.2 3.2 0 0 1 6.4 0v3.5')

@icon('outdoor')
def _(i): i.path('M12 3c5 3.5 6 9 2.4 12.6L12 17.5l-2.4-1.9C6 12 7 6.5 12 3Z', acc=True).path('M12 3.1c5 3.4 6 9 2.4 12.6L12 17.5l-2.4-1.9C6 12 7 6.6 12 3.1Z').line(12, 9, 12, 21)

@icon('tent')
def _(i): i.poly([(2.5, 20), (12, 4.5), (21.5, 20)], close=True).poly([(12, 9.5), (8.5, 20), (15.5, 20)], close=True, acc=True).poly([(12, 9.5), (8.5, 20), (15.5, 20)], close=True)

@icon('fire')
def _(i): i.path('M12 3c1 3 5 5 5 9.5A5 5 0 0 1 7 12.5C7 10 8.5 9 9.2 7.5c.7 1.6 1.6 2.3 2.6 2.6C11 7.8 11 5.2 12 3Z', acc=True).path('M12 3.1c1 2.9 5 4.9 5 9.4a5 5 0 0 1-10 0C7 10 8.5 9 9.2 7.6c.7 1.5 1.6 2.2 2.6 2.5C11 7.8 11 5.2 12 3.1Z').line(5, 21, 19, 18.5).line(5, 18.5, 19, 21)

@icon('gate')
def _(i): i.line(3, 6, 21, 6).line(5, 6, 5, 20).line(19, 6, 19, 20).rrect(8, 9, 8, 4, 1, acc=True).rrect(8, 9, 8, 4, 1)

@icon('wristband')
def _(i): i.circle(10, 12, 6.8).circle(10, 12, 4.2, acc=True).rrect(15, 8.5, 6, 7, 1.5, acc=True).rrect(15, 8.5, 6, 7, 1.5).dot(18, 12, 0.9)

@icon('mic')
def _(i): i.rrect(9, 3, 6, 11, 3, acc=True).rrect(9, 3, 6, 11, 3).path('M5.5 11a6.5 6.5 0 0 0 13 0').line(12, 17.5, 12, 21).line(8.5, 21, 15.5, 21)

@icon('camera')
def _(i): i.rrect(3, 7, 18, 13, 2.5, acc=True).rrect(3, 7, 18, 13, 2.5).path('M8 7l1.5-2.5h5L16 7').circle(12, 13.5, 3.6)

# ---------------------------------------------------------------- facilities
@icon('surau')
def _(i): i.path('M5 20v-6.5a7 6.5 0 0 1 14 0V20Z', acc=True).path('M5 20.1v-6.6a7 6.5 0 0 1 14 0V20').line(3, 20, 21, 20).line(12, 7, 12, 3.5).path('M10.6 4.4a1.6 1.6 0 1 0 2.2 2').path('M10 20v-3.5a2 2 0 0 1 4 0V20')

@icon('toilet')
def _(i): i.circle(7, 5, 1.8).circle(17, 5, 1.8).rrect(5, 8.5, 4, 6.5, 1.2, acc=True).rrect(5, 8.5, 4, 6.5, 1.2).line(6, 15, 6, 20).line(8, 15, 8, 20).poly([(17, 8.5), (19.8, 15.5), (14.2, 15.5)], close=True, acc=True).poly([(17, 8.5), (19.8, 15.5), (14.2, 15.5)], close=True).line(16, 15.5, 16, 20).line(18, 15.5, 18, 20).line(12, 6, 12, 20)

@icon('first-aid')
def _(i): i.rrect(3.5, 3.5, 17, 17, 4, acc=True).rrect(3.5, 3.5, 17, 17, 4).line(12, 7.5, 12, 16.5).line(7.5, 12, 16.5, 12)

@icon('water')
def _(i): i.path('M12 3.5C12 3.5 6 10.2 6 14.3a6 6 0 0 0 12 0C18 10.2 12 3.5 12 3.5Z', acc=True).path('M12 3.6C12 3.6 6 10.2 6 14.3a6 6 0 0 0 12 0c0-4.1-6-10.7-6-10.7Z').path('M9.3 14.8a2.8 2.8 0 0 0 2.4 2.6')

@icon('parking')
def _(i): i.rrect(3.5, 3.5, 17, 17, 4, acc=True).rrect(3.5, 3.5, 17, 17, 4).path('M9.5 17V7h3.5a3 3 0 0 1 0 6H9.5')

@icon('lost-found')
def _(i): i.path('M4 9h16v11H4Z', acc=True).path('M4 9.1h16V20H4Z').path('M3 9l2.5-4.5h13L21 9').path('M10 12.5a2 2 0 1 1 2.6 1.9c-.5.2-.6.6-.6 1.1').dot(12, 17.6)

# ---------------------------------------------------------------- tags and plan
@icon('leaf')
def _(i): i.path('M5 19C5 10 10 5 20 4c-1 10-6 15-15 15Z', acc=True).path('M5 19.1C5 10 10 5.1 19.9 4.1 19 14 14 19 5 19.1Z').line(5, 19, 13, 11)

@icon('card')
def _(i): i.rrect(3, 6, 18, 12.5, 2.5, acc=True).rrect(3, 6, 18, 12.5, 2.5).line(3, 10, 21, 10).line(6.5, 15, 10, 15)

@icon('cash')
def _(i): i.rrect(2.5, 6.5, 19, 11, 2, acc=True).rrect(2.5, 6.5, 19, 11, 2).circle(12, 12, 2.6).dot(6, 12, 0.9).dot(18, 12, 0.9)

@icon('moon')
def _(i): i.path('M20 14.5A8.5 8.5 0 1 1 9.5 4 6.8 6.8 0 0 0 20 14.5Z', acc=True).path('M20 14.6A8.5 8.5 0 1 1 9.5 4.1a6.8 6.8 0 0 0 10.5 10.5Z')

@icon('sun')
def _(i):
    i.circle(12, 12, 4.2, acc=True).circle(12, 12, 4.2)
    for k in range(8):
        a = math.radians(k * 45)
        i.line(12 + 6.8 * math.cos(a), 12 + 6.8 * math.sin(a), 12 + 9.3 * math.cos(a), 12 + 9.3 * math.sin(a))

@icon('rain')
def _(i): i.path('M7 14.5a4 4 0 0 1-.4-8A5.5 5.5 0 0 1 17 6.6a3.9 3.9 0 0 1 .5 7.9Z', acc=True).path('M7 14.6a4 4 0 0 1-.4-8 5.5 5.5 0 0 1 10.4 0 3.9 3.9 0 0 1 .5 7.9Z').line(8, 17, 7, 20).line(12, 17, 11, 20).line(16, 17, 15, 20)

@icon('person')
def _(i): i.circle(12, 7.5, 3.5, acc=True).circle(12, 7.5, 3.5).path('M5 20.5a7 7 0 0 1 14 0')

@icon('geng')
def _(i): i.circle(8.5, 8, 3, acc=True).circle(8.5, 8, 3).circle(16.5, 9, 2.5).path('M3 20a5.5 5.5 0 0 1 11 0').path('M14.5 14.6A4.6 4.6 0 0 1 21 18.6')

@icon('car')
def _(i): i.path('M3.5 16v-3.5L6 7.5h12l2.5 5V16Z', acc=True).path('M3.5 16.1v-3.6L6 7.6h12l2.5 4.9V16Z').line(3.5, 12.5, 20.5, 12.5).circle(7.5, 16.5, 2).circle(16.5, 16.5, 2)

@icon('backpack')
def _(i): i.rrect(5.5, 6.5, 13, 14, 4, acc=True).rrect(5.5, 6.5, 13, 14, 4).path('M9 6.5V5a3 3 0 0 1 6 0v1.5').rrect(8.5, 13, 7, 4.5, 1.2)

@icon('cup')
def _(i): i.path('M6 6h12l-1.6 14H7.6Z', acc=True).path('M6 6.1h12l-1.6 13.9H7.6Z').line(5, 6, 19, 6).line(6.6, 11, 17.4, 11)

# ---------------------------------------------------------------- social
@icon('instagram')
def _(i): i.rrect(3.5, 3.5, 17, 17, 5).circle(12, 12, 4).dot(17, 7, 1.1)

@icon('tiktok')
def _(i): i.path('M13 3.5v11.8a3.6 3.6 0 1 1-3.6-3.6').path('M13 3.6c.4 2.6 2.2 4.4 5 4.6')

@icon('x')
def _(i): i.line(4.5, 4.5, 19.5, 19.5).line(7.5, 4.5, 20, 17).line(19.5, 4.5, 13.2, 11.2).line(4.5, 19.5, 10.8, 12.8)

@icon('facebook')
def _(i): i.path('M14.5 21v-8h3l.5-3.5h-3.5V7.6c0-1 .4-1.7 1.8-1.7H18V2.8a24 24 0 0 0-2.7-.2c-2.8 0-4.4 1.6-4.4 4.6v2.3H8V13h2.9v8')

@icon('whatsapp')
def _(i): i.path('M4 20l1.2-4.2A8.5 8.5 0 1 1 8.4 19Z', acc=True).path('M4 20.1l1.2-4.3a8.5 8.5 0 1 1 3.2 3.2Z').path('M9 8.5c0 3.5 3 6.5 6.5 6.5l1-1.6-2-1-1 .8a4.6 4.6 0 0 1-2.2-2.2l.8-1-1-2Z')

@icon('youtube')
def _(i): i.rrect(2.5, 5.5, 19, 13, 4, acc=True).rrect(2.5, 5.5, 19, 13, 4).poly([(10, 9), (15, 12), (10, 15)], close=True)


def build():
    os.makedirs(OUT, exist_ok=True)
    symbols = [ic.svg() for ic in ICONS]
    sprite = ('<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" '
              'style="position:absolute;width:0;height:0;overflow:hidden">'
              + ''.join(symbols) + '</svg>\n')
    with open(os.path.join(OUT, 'sprite.svg'), 'w') as fh:
        fh.write(sprite)
    with open(os.path.join(OUT, 'icons.json'), 'w') as fh:
        json.dump([ic.name for ic in ICONS], fh, indent=1)
    print(len(ICONS), 'icons,', len(sprite), 'bytes')


if __name__ == '__main__':
    build()
